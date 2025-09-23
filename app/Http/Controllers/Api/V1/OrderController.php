<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['user', 'event', 'coupon', 'orderItems.ticketType']);

        // Filter by event
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
        ]);
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'order_items' => 'required|array|min:1',
            'order_items.*.ticket_type_id' => 'required|exists:ticket_types,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'required|string|in:stripe,card,cash',
            'billing_details' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check event availability
            $event = Event::with('ticketTypes')->find($request->event_id);

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            foreach ($request->order_items as $item) {
                $ticketType = $event->ticketTypes->find($item['ticket_type_id']);
                if (!$ticketType || $ticketType->quantity_available < $item['quantity']) {
                    return response()->json([
                        'message' => 'Ticket type not available in requested quantity',
                    ], 422);
                }
                $subtotal += $ticketType->price * $item['quantity'];
            }

            // Apply tax (assuming 10% tax)
            $taxAmount = $subtotal * 0.1;

            // Apply coupon if provided
            $couponId = null;
            if ($request->has('coupon_code')) {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
                    ->where('is_active', true)
                    ->first();

                if ($coupon && $coupon->isValid()) {
                    $discountAmount = min($subtotal * ($coupon->discount_percentage / 100), $coupon->max_discount_amount ?? PHP_INT_MAX);
                    $couponId = $coupon->id;
                }
            }

            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'event_id' => $request->event_id,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'billing_details' => $request->billing_details,
                'coupon_id' => $couponId,
                'currency' => 'USD',
            ]);

            // Create order items
            foreach ($request->order_items as $item) {
                $ticketType = $event->ticketTypes->find($item['ticket_type_id']);
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $item['ticket_type_id'],
                    'quantity' => $item['quantity'],
                    'price' => $ticketType->price,
                    'total' => $ticketType->price * $item['quantity'],
                ]);
            }

            $order->load(['user', 'event', 'coupon', 'orderItems.ticketType']);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::with(['user', 'event', 'coupon', 'orderItems.ticketType', 'tickets'])
            ->find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        // Check if user can view this order
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'message' => 'Order retrieved successfully',
            'data' => $order,
        ]);
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        // Check if user can update this order
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only allow updating certain fields for pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot update completed order',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'billing_details' => 'sometimes|array',
            'payment_method' => 'sometimes|string|in:stripe,card,cash',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $order->update($request->only(['billing_details', 'payment_method']));
        $order->load(['user', 'event', 'coupon', 'orderItems.ticketType']);

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $order,
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        // Check if user can delete this order
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only allow deleting pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot delete completed order',
            ], 422);
        }

        try {
            $order->delete();

            return response()->json([
                'message' => 'Order deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get orders for the authenticated user.
     */
    public function myOrders(Request $request): JsonResponse
    {
        $query = Order::with(['event', 'coupon', 'orderItems.ticketType'])
            ->where('user_id', auth()->id());

        // Filter by event
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'My orders retrieved successfully',
            'data' => $orders,
        ]);
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        // Check if user can cancel this order
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only allow canceling pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot cancel completed order',
            ], 422);
        }

        try {
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'message' => 'Order cancelled successfully',
                'data' => $order->load(['event', 'coupon', 'orderItems.ticketType']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error cancelling order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
