<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Coupon::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Search by code or name
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Coupons retrieved successfully',
            'data' => $coupons,
        ]);
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:percentage,fixed',
            'value' => 'required|numeric|min:0|max:100',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_events' => 'nullable|array',
            'applicable_events.*' => 'integer|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $coupon = Coupon::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'minimum_amount' => $request->minimum_amount,
            'usage_limit' => $request->usage_limit,
            'usage_limit_per_user' => $request->usage_limit_per_user,
            'used_count' => 0,
            'valid_from' => $request->valid_from,
            'valid_until' => $request->valid_until,
            'is_active' => $request->is_active ?? true,
            'applicable_events' => $request->applicable_events,
        ]);

        return response()->json([
            'message' => 'Coupon created successfully',
            'data' => $coupon,
        ], 201);
    }

    /**
     * Display the specified coupon.
     */
    public function show(string $id): JsonResponse
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Coupon retrieved successfully',
            'data' => $coupon,
        ]);
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'sometimes|required|string|max:50|unique:coupons,code,' . $id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|in:percentage,fixed',
            'value' => 'sometimes|required|numeric|min:0|max:100',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'valid_from' => 'sometimes|required|date',
            'valid_until' => 'sometimes|required|date|after:valid_from',
            'is_active' => 'boolean',
            'applicable_events' => 'nullable|array',
            'applicable_events.*' => 'integer|exists:events,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $coupon->update($request->only([
            'code', 'name', 'description', 'type', 'value', 'minimum_amount',
            'usage_limit', 'usage_limit_per_user', 'valid_from', 'valid_until',
            'is_active', 'applicable_events'
        ]));

        return response()->json([
            'message' => 'Coupon updated successfully',
            'data' => $coupon,
        ]);
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found',
            ], 404);
        }

        // Check if coupon has been used
        if ($coupon->used_count > 0) {
            return response()->json([
                'message' => 'Cannot delete coupon that has been used',
            ], 422);
        }

        $coupon->delete();

        return response()->json([
            'message' => 'Coupon deleted successfully',
        ]);
    }

    /**
     * Validate a coupon code.
     */
    public function validate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'order_amount' => 'required|numeric|min:0',
            'event_id' => 'required|integer|exists:events,id',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $coupon = Coupon::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'message' => 'Coupon not found or inactive',
                'valid' => false,
            ], 404);
        }

        $isValid = $coupon->isValid(
            $request->order_amount,
            $request->event_id,
            $request->user_id
        );

        if (!$isValid) {
            return response()->json([
                'message' => 'Coupon is not valid for this order',
                'valid' => false,
            ], 422);
        }

        $discountAmount = $coupon->calculateDiscount($request->order_amount);

        return response()->json([
            'message' => 'Coupon is valid',
            'valid' => true,
            'data' => [
                'coupon' => $coupon,
                'discount_amount' => $discountAmount,
            ],
        ]);
    }
}
