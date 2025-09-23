<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of ticket types.
     */
    public function index(Request $request): JsonResponse
    {
        $query = TicketType::with(['event']);

        // Filter by event
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $ticketTypes = $query->orderBy('sort_order')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Ticket types retrieved successfully',
            'data' => $ticketTypes,
        ]);
    }

    /**
     * Store a newly created ticket type in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity_total' => 'required|integer|min:1',
            'quantity_available' => 'nullable|integer|min:0',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'min_purchase' => 'nullable|integer|min:1',
            'max_purchase' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check if user can create ticket types for this event
            $event = Event::find($request->event_id);
            if (auth()->id() !== $event->organizer_id && !auth()->user()->hasRole('admin')) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Set default values
            $quantityAvailable = $request->quantity_available ?? $request->quantity_total;

            $ticketType = TicketType::create([
                'event_id' => $request->event_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity_total' => $request->quantity_total,
                'quantity_available' => $quantityAvailable,
                'sale_start' => $request->sale_start,
                'sale_end' => $request->sale_end,
                'min_purchase' => $request->min_purchase ?? 1,
                'max_purchase' => $request->max_purchase ?? $request->quantity_total,
                'is_active' => $request->is_active ?? true,
                'benefits' => $request->benefits,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            $ticketType->load(['event']);

            DB::commit();

            return response()->json([
                'message' => 'Ticket type created successfully',
                'data' => $ticketType,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified ticket type.
     */
    public function show(string $id): JsonResponse
    {
        $ticketType = TicketType::with(['event'])->find($id);

        if (!$ticketType) {
            return response()->json([
                'message' => 'Ticket type not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket type retrieved successfully',
            'data' => $ticketType,
        ]);
    }

    /**
     * Update the specified ticket type in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $ticketType = TicketType::find($id);

        if (!$ticketType) {
            return response()->json([
                'message' => 'Ticket type not found',
            ], 404);
        }

        // Check if user can update this ticket type
        if (auth()->id() !== $ticketType->event->organizer_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'quantity_total' => 'sometimes|integer|min:1',
            'quantity_available' => 'sometimes|integer|min:0',
            'sale_start' => 'nullable|date',
            'sale_end' => 'nullable|date|after:sale_start',
            'min_purchase' => 'nullable|integer|min:1',
            'max_purchase' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $ticketType->update($request->only([
                'name', 'description', 'price', 'quantity_total', 'quantity_available',
                'sale_start', 'sale_end', 'min_purchase', 'max_purchase',
                'is_active', 'benefits', 'sort_order'
            ]));

            $ticketType->load(['event']);

            return response()->json([
                'message' => 'Ticket type updated successfully',
                'data' => $ticketType,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified ticket type from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $ticketType = TicketType::find($id);

        if (!$ticketType) {
            return response()->json([
                'message' => 'Ticket type not found',
            ], 404);
        }

        // Check if user can delete this ticket type
        if (auth()->id() !== $ticketType->event->organizer_id && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if ticket type has been sold
        if ($ticketType->quantity_total - $ticketType->quantity_available > 0) {
            return response()->json([
                'message' => 'Cannot delete ticket type that has been sold',
            ], 422);
        }

        try {
            $ticketType->delete();

            return response()->json([
                'message' => 'Ticket type deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting ticket type',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
