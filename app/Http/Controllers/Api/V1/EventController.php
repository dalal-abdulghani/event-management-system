<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EventController extends Controller
{
    // Middleware is now handled in routes/api.php

    /**
     * Display all events (for admin users).
     */
    public function allEvents(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check if user is admin
        if ($user && $user->hasRole('admin')) {
            $query = Event::with(['venue', 'ticketTypes', 'organizer']);
        } else {
            // For non-admin users, only show their events
            $query = Event::with(['venue', 'ticketTypes'])
                ->where('organizer_id', auth()->id());
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $events = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Events retrieved successfully',
            'data' => $events
        ]);
    }

    /**
     * Display a listing of events.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::with(['venue', 'organizer', 'ticketTypes'])
            ->published();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category/tags
        if ($request->has('category')) {
            $query->whereJsonContains('tags', $request->get('category'));
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('start_date', '>=', $request->get('start_date'));
        }

        if ($request->has('end_date')) {
            $query->where('end_date', '<=', $request->get('end_date'));
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('min_price', '>=', $request->get('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('max_price', '<=', $request->get('max_price'));
        }

        // Filter by location
        if ($request->has('city')) {
            $query->whereHas('venue', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->get('city') . '%');
            });
        }

        // Featured events
        if ($request->has('featured')) {
            $query->featured();
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'start_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Events retrieved successfully',
            'data' => $events,
        ]);
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'nullable|date|before:start_date',
            'registration_end' => 'nullable|date|before:start_date|after:registration_start',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'cancellation_policy' => 'nullable|string',
            'additional_info' => 'nullable|array',
            'total_capacity' => 'nullable|integer|min:1',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $event = Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'slug' => Str::slug($request->title) . '-' . time(),
                'venue_id' => $request->venue_id,
                'organizer_id' => auth()->id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'registration_start' => $request->registration_start,
                'registration_end' => $request->registration_end,
                'status' => 'draft',
                'images' => $request->images,
                'cancellation_policy' => $request->cancellation_policy,
                'additional_info' => $request->additional_info,
                'total_capacity' => $request->total_capacity,
                'available_capacity' => $request->total_capacity,
                'tags' => $request->tags,
            ]);

            $event->load(['venue', 'organizer', 'ticketTypes']);

            DB::commit();

            return response()->json([
                'message' => 'Event created successfully',
                'data' => $event,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Error creating event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified event.
     */
    public function show(string $id): JsonResponse
    {
        $event = Event::with([
            'venue', 
            'organizer', 
            'ticketTypes' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            },
            'sessions' => function ($query) {
                $query->orderBy('sort_order')->orderBy('start_time');
            }
        ])->find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found',
            ], 404);
        }

        // Check if user can view draft events
        if ($event->status === 'draft' && 
            (!auth()->check() || auth()->id() !== $event->organizer_id)) {
            return response()->json([
                'message' => 'Event not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Event retrieved successfully',
            'data' => $event,
        ]);
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found',
            ], 404);
        }

        // Check if user can edit this event
        if (auth()->id() !== $event->organizer_id && !auth()->user()->can('edit events')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'venue_id' => 'sometimes|required|exists:venues,id',
            'start_date' => 'sometimes|required|date|after:now',
            'end_date' => 'sometimes|required|date|after:start_date',
            'registration_start' => 'nullable|date|before:start_date',
            'registration_end' => 'nullable|date|before:start_date',
            'status' => 'sometimes|in:draft,published,cancelled',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'cancellation_policy' => 'nullable|string',
            'additional_info' => 'nullable|array',
            'is_featured' => 'boolean',
            'total_capacity' => 'nullable|integer|min:1',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $event->update($request->only([
                'title', 'description', 'short_description', 'venue_id',
                'start_date', 'end_date', 'registration_start', 'registration_end',
                'status', 'images', 'cancellation_policy', 'additional_info',
                'is_featured', 'total_capacity', 'tags'
            ]));

            // Update slug if title changed
            if ($request->has('title')) {
                $event->update(['slug' => Str::slug($request->title) . '-' . $event->id]);
            }

            $event->load(['venue', 'organizer', 'ticketTypes']);

            return response()->json([
                'message' => 'Event updated successfully',
                'data' => $event,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified event.
     */
    public function destroy(string $id): JsonResponse
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found',
            ], 404);
        }

        // Check if user can delete this event
        if (auth()->id() !== $event->organizer_id && !auth()->user()->can('delete events')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if event has orders
        if ($event->orders()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete event with existing orders',
            ], 422);
        }

        try {
            $event->delete();

            return response()->json([
                'message' => 'Event deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting event',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get events for the authenticated organizer.
     */
    public function myEvents(Request $request): JsonResponse
    {
        $query = Event::with(['venue', 'ticketTypes'])
            ->where('organizer_id', auth()->id());

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $events = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'My events retrieved successfully',
            'data' => $events,
        ]);
    }

    /**
     * Publish an event.
     */
    public function publish(string $id): JsonResponse
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found',
            ], 404);
        }

        if (auth()->id() !== $event->organizer_id && !auth()->user()->can('publish events')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Validate event has ticket types
        if ($event->ticketTypes()->count() === 0) {
            return response()->json([
                'message' => 'Cannot publish event without ticket types',
            ], 422);
        }

        $event->update(['status' => 'published']);

        return response()->json([
            'message' => 'Event published successfully',
            'data' => $event,
        ]);
    }
}
