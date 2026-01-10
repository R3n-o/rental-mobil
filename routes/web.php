<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\BookingController;

Route::get('/', [HomeController::class, 'index']);


Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('check.session')->group(function() {
    
    Route::get('/logout', [AuthController::class, 'logout']);

    
    Route::get('/bookings/create/{id}', [BookingController::class, 'create']); 
    Route::post('/bookings', [BookingController::class, 'store']);             
    Route::get('/bookings', [BookingController::class, 'index']);              
    
    Route::get('/bookings/payment/{id}', [BookingController::class, 'paymentForm']); 
    Route::post('/bookings/payment', [BookingController::class, 'processPayment']);  

    Route::prefix('admin')->group(function() {
        
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        
        Route::post('/cars', [AdminController::class, 'storeCar']);
        Route::put('/cars/{id}', [AdminController::class, 'updateCar']);
        Route::delete('/cars/{id}', [AdminController::class, 'destroyCar']);
        
        Route::get('/bookings', [AdminController::class, 'bookings']);
        Route::put('/payments/{id}', [AdminController::class, 'verifyPayment']);
    });

    Route::prefix('admin')->group(function() {
        Route::post('/categories', [AdminController::class, 'storeCategory']);
        Route::patch('/cars/{id}/status', [AdminController::class, 'updateCarStatus']);
    });

    Route::post('/reviews', [BookingController::class, 'storeReview']);

    Route::delete('/bookings/{id}', [\App\Http\Controllers\Web\BookingController::class, 'destroy'])->name('bookings.destroy');

});