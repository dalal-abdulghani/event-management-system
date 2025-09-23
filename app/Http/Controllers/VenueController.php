<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $query = Venue::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('state')) {
            $query->where('state', $request->state);
        }

        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', $request->capacity_min);
        }

        if ($request->filled('capacity_max')) {
            $query->where('capacity', '<=', $request->capacity_max);
        }

        // Show only active venues for public access
        if (!Auth::check()) {
            $query->where('is_active', true);
        }

        $venues = $query->latest()->paginate(15);

        return view('app', compact('venues'));
    }

    public function show(Venue $venue)
    {
        // Check if venue is active for public access
        if (!$venue->is_active && !Auth::check()) {
            abort(403);
        }

        $venue->load(['events' => function ($query) {
            $query->where('status', 'published')->latest();
        }]);

        return view('app', compact('venue'));
    }

    public function create()
    {
        return view('app');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('venues', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $venue = Venue::create($data);

        return redirect()->route('venues.show', $venue)
            ->with('success', 'Venue created successfully!');
    }

    public function edit(Venue $venue)
    {
        return view('app', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'is_active' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('venues', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $venue->update($data);

        return redirect()->route('venues.show', $venue)
            ->with('success', 'Venue updated successfully!');
    }

    public function destroy(Venue $venue)
    {
        // Check if venue has events
        if ($venue->events()->count() > 0) {
            return back()->with('error', 'Cannot delete venue with existing events.');
        }

        $venue->delete();

        return redirect()->route('venues.index')
            ->with('success', 'Venue deleted successfully!');
    }
}
