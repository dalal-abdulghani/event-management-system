<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Ticket::with(['event', 'ticketType', 'user', 'order']);

        // Filter by event
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by ticket type
        if ($request->has('ticket_type_id')) {
            $query->where('ticket_type_id', $request->ticket_type_id);
        }

        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Tickets retrieved successfully',
            'data' => $tickets,
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'event_id' => 'required|exists:events,id',
            'user_id' => 'nullable|exists:users,id',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'additional_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket = Ticket::create($request->only([
            'order_id', 'order_item_id', 'ticket_type_id', 'event_id',
            'user_id', 'attendee_name', 'attendee_email', 'additional_info'
        ]));

        $ticket->load(['event', 'ticketType', 'user', 'order']);

        return response()->json([
            'message' => 'Ticket created successfully',
            'data' => $ticket,
        ], 201);
    }

    /**
     * Display the specified ticket.
     */
    public function show(string $id): JsonResponse
    {
        $ticket = Ticket::with(['event', 'ticketType', 'user', 'order', 'checkedInBy'])
            ->find($id);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Ticket retrieved successfully',
            'data' => $ticket,
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'attendee_name' => 'sometimes|required|string|max:255',
            'attendee_email' => 'sometimes|required|email|max:255',
            'status' => 'sometimes|in:valid,used,cancelled',
            'additional_info' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $ticket->update($request->only([
            'attendee_name', 'attendee_email', 'status', 'additional_info'
        ]));

        $ticket->load(['event', 'ticketType', 'user', 'order']);

        return response()->json([
            'message' => 'Ticket updated successfully',
            'data' => $ticket,
        ]);
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        // Check if ticket has been used
        if ($ticket->status === 'used') {
            return response()->json([
                'message' => 'Cannot delete used ticket',
            ], 422);
        }

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted successfully',
        ]);
    }

    /**
     * Get tickets for the authenticated user.
     */
    public function myTickets(Request $request): JsonResponse
    {
        $query = Ticket::with(['event', 'ticketType', 'order'])
            ->where('user_id', auth()->id());

        // Filter by event
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'My tickets retrieved successfully',
            'data' => $tickets,
        ]);
    }

    /**
     * Check in a ticket.
     */
    public function checkIn(Request $request, string $id): JsonResponse
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }

        if (!$ticket->isValidForCheckIn()) {
            return response()->json([
                'message' => 'Ticket is not valid for check-in',
            ], 422);
        }

        $ticket->checkIn(auth()->user());

        return response()->json([
            'message' => 'Ticket checked in successfully',
            'data' => $ticket->load(['event', 'ticketType', 'checkedInBy']),
        ]);
    }
}
