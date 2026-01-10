<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $booking = Booking::find($request->booking_id);
        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized. Ini bukan booking Anda.'], 403);
        }

        if (!in_array($booking->status, ['confirmed', 'finished'])) {
            return response()->json(['message' => 'Anda hanya bisa mereview mobil setelah booking disetujui atau selesai.'], 400);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'car_id'  => $booking->car_id, 
            'rating'  => $request->rating,
            'comment' => $request->comment
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan',
            'data'    => $review
        ], 201);
    }
}