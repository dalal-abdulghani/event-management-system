<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['venue', 'ticketTypes']);

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('venue_id')) {
            $query->where('venue_id', $request->venue_id);
        }

        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_date', '<=', $request->end_date);
        }

        // Show only user's events for authenticated users
        if (Auth::check()) {
            $query->where('organizer_id', Auth::id());
        } else {
            // For public access, only show published events
            $query->where('status', 'published');
        }

        $events = $query->latest()->paginate(15);
        $venues = Venue::where('is_active', true)->get();

        return view('app', compact('events', 'venues'));
    }

    public function show(Event $event)
    {
        // Check if user can view this event
        if ($event->status !== 'published' && (!Auth::check() || $event->organizer_id !== Auth::id())) {
            abort(403);
        }

        $event->load(['venue', 'ticketTypes', 'organizer']);
        
        return view('app', compact('event'));
    }

    public function create()
    {
        $venues = Venue::where('is_active', true)->get();
        return view('app', compact('venues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'nullable|date|before:start_date',
            'registration_end' => 'nullable|date|after:registration_start|before:start_date',
            'total_capacity' => 'nullable|integer|min:1',
            'cancellation_policy' => 'nullable|string',
            'tags' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['organizer_id'] = Auth::id();
        $data['slug'] = Str::slug($request->title);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('events', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $event = Event::create($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        // Check if user can edit this event
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $venues = Venue::where('is_active', true)->get();
        
        return view('app', compact('event', 'venues'));
    }

    public function update(Request $request, Event $event)
    {
        // Check if user can update this event
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'venue_id' => 'required|exists:venues,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'nullable|date|before:start_date',
            'registration_end' => 'nullable|date|after:registration_start|before:start_date',
            'status' => 'required|in:draft,published,cancelled',
            'total_capacity' => 'nullable|integer|min:1',
            'cancellation_policy' => 'nullable|string',
            'tags' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('events', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $event->update($data);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        // Check if user can delete this event
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function publish(Event $event)
    {
        // Check if user can publish this event
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $event->update(['status' => 'published']);

        return response()->json([
            'message' => 'Event published successfully',
            'event' => $event
        ]);
    }
}
