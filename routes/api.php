<?php

use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\NotificationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public API routes
Route::get('/menus', [MenuApiController::class, 'index']);
Route::get('/menus/{menu}', [MenuApiController::class, 'show']);
Route::get('/menus/category/{category}', [MenuApiController::class, 'byCategory']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Menu routes (admin only in controller)
    Route::post('/menus', [MenuApiController::class, 'store']);
    Route::put('/menus/{menu}', [MenuApiController::class, 'update']);
    Route::delete('/menus/{menu}', [MenuApiController::class, 'destroy']);
    
    // Order routes
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/user', [OrderApiController::class, 'userOrders']);
    Route::get('/orders/{order}', [OrderApiController::class, 'show']);
    Route::put('/orders/{order}', [OrderApiController::class, 'update']);
    Route::delete('/orders/{order}', [OrderApiController::class, 'destroy']);
    
    // Notification routes
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
    Route::get('/notifications/unread-count', [NotificationApiController::class, 'unreadCount']);
});