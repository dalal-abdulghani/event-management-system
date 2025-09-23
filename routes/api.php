<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\TicketTypeController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\VenueController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Middleware\Cors;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These API routes are specifically for the Vue SPA frontend.
| They provide JSON responses for AJAX requests within the SPA.
|
*/

// Public API routes (no authentication required)
Route::middleware([Cors::class])->prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    // Public event routes
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);

    // Public venue routes
    Route::get('/venues', [VenueController::class, 'index']);
    Route::get('/venues/{id}', [VenueController::class, 'show']);
});

// Protected API routes for SPA
Route::middleware(['auth:sanctum', Cors::class])->prefix('v1')->group(function () {
    
    // User info
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Dashboard API
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    
    // Events API for SPA
    Route::get('/my-events', [EventController::class, 'myEvents']);
    Route::get('/all-events', [EventController::class, 'allEvents']);
    Route::post('/events/{id}/publish', [EventController::class, 'publish']);
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    
    // Ticket Types API
    Route::apiResource('events.ticket-types', TicketTypeController::class);
    
    // Venues API for SPA
    Route::apiResource('venues', VenueController::class)->except(['index', 'show']);
    
    // Orders API
    Route::get('/my-orders', [OrderController::class, 'myOrders']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::apiResource('orders', OrderController::class);
    
    // Tickets API
    Route::get('/my-tickets', [TicketController::class, 'myTickets']);
    Route::post('/tickets/{id}/checkin', [TicketController::class, 'checkIn']);
    Route::apiResource('tickets', TicketController::class)->only(['index', 'show']);
    
    // Coupons API
    Route::post('/coupons/validate', [CouponController::class, 'validate']);
    Route::apiResource('coupons', CouponController::class);
});
