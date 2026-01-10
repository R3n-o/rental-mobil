<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReviewController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('categories', [CategoryController::class, 'index']);
Route::get('cars', [CarController::class, 'index']);
Route::get('cars/{id}', [CarController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    Route::post('cars', [CarController::class, 'store']);
    Route::put('cars/{id}', [CarController::class, 'update']); 
    Route::delete('cars/{id}', [CarController::class, 'destroy']);

    Route::get('bookings', [BookingController::class, 'index']);
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);


    Route::post('payments', [PaymentController::class, 'store']); 
    Route::put('payments/{id}', [PaymentController::class, 'update']);

    Route::post('reviews', [ReviewController::class, 'store']);

    Route::patch('cars/{id}/status', [CarController::class, 'updateStatus']);

  
    Route::get('activity-logs', function() {
    if (auth()->user()->role !== 'admin') {
        return response()->json(['message' => 'Unauthorized'], 403);
    }
    $logs = \App\Models\ActivityLog::with('user')->latest()->get();

    return response()->json([
        'success' => true,
        'data' => $logs
    ]);
    });

    Route::delete('bookings/{id}', [BookingController::class, 'destroy']);


});