<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    // Middleware is now handled in routes/api.php

    /**
     * Display a listing of venues.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Venue::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->get('city') . '%');
        }

        // Filter by state
        if ($request->has('state')) {
            $query->where('state', 'like', '%' . $request->get('state') . '%');
        }

        // Filter by capacity
        if ($request->has('capacity_min')) {
            $query->where('capacity', '>=', $request->get('capacity_min'));
        }

        if ($request->has('capacity_max')) {
            $query->where('capacity', '<=', $request->get('capacity_max'));
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->get('is_active'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $venues = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'message' => 'Venues retrieved successfully',
            'data' => $venues,
        ]);
    }

    /**
     * Store a newly created venue.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $venue = Venue::create([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'capacity' => $request->capacity,
                'amenities' => $request->amenities,
                'images' => $request->images,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'website' => $request->website,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'Venue created successfully',
                'data' => $venue,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating venue',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified venue.
     */
    public function show(string $id): JsonResponse
    {
        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'message' => 'Venue not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Venue retrieved successfully',
            'data' => $venue,
        ]);
    }

    /**
     * Update the specified venue.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'message' => 'Venue not found',
            ], 404);
        }

        // Check if user can edit this venue (admin or owner)
        if (!auth()->user()->can('edit venues') && !auth()->user()->can('edit all venues')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'country' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'capacity' => 'sometimes|required|integer|min:1',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'website' => 'nullable|url',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $venue->update($request->only([
                'name', 'address', 'city', 'state', 'country', 'postal_code',
                'capacity', 'amenities', 'images', 'contact_email',
                'contact_phone', 'website', 'is_active'
            ]));

            return response()->json([
                'message' => 'Venue updated successfully',
                'data' => $venue,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating venue',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified venue.
     */
    public function destroy(string $id): JsonResponse
    {
        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'message' => 'Venue not found',
            ], 404);
        }

        // Check if user can delete this venue
        if (!auth()->user()->can('delete venues') && !auth()->user()->can('delete all venues')) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if venue has events
        if ($venue->events()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete venue with existing events',
            ], 422);
        }

        try {
            $venue->delete();

            return response()->json([
                'message' => 'Venue deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting venue',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}