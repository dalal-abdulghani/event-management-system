<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect('/login');
        }

        // Get user's events
        $events = Event::where('organizer_id', $user->id)
            ->with(['ticketTypes', 'venue'])
            ->latest()
            ->paginate(10);

        // Get recent orders for user's events
        $eventIds = $events->pluck('id');
        $recentOrders = Order::whereIn('event_id', $eventIds)
            ->with(['event', 'orderItems'])
            ->latest()
            ->limit(5)
            ->get();

        // Get stats
        $stats = [
            'total_events' => Event::where('organizer_id', $user->id)->count(),
            'total_tickets_sold' => Ticket::whereHas('orderItem.order', function($query) use ($user) {
                $query->whereHas('event', function($q) use ($user) {
                    $q->where('organizer_id', $user->id);
                });
            })->count(),
            'total_revenue' => Order::whereHas('event', function($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })->where('status', 'completed')->sum('total_amount'),
            'pending_orders' => Order::whereHas('event', function($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })->where('status', 'pending')->count(),
        ];

        return view('app', compact('events', 'recentOrders', 'stats'));
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get monthly stats for the last 12 months
        $monthlyStats = Order::whereHas('event', function($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'monthly_stats' => $monthlyStats,
            'event_types' => Event::where('organizer_id', $user->id)
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->get(),
        ]);
    }
}
