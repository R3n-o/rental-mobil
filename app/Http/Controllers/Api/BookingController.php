<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $bookings = Booking::with(['user', 'car', 'payment'])->get();
        } else {
            $bookings = Booking::with(['car', 'payment'])
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc') 
                            ->get();
        }

        return response()->json([
            'success' => true,
            'data'    => $bookings
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id'     => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $car = Car::find($request->car_id);

        $isBooked = Booking::where('car_id', $request->car_id)
            ->whereIn('status', ['pending', 'confirmed', 'ongoing'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<', $request->start_date)
                            ->where('end_date', '>', $request->end_date);
                      });
            })->exists();

        if ($isBooked) {
            return response()->json([
                'message' => 'Mobil tidak tersedia pada tanggal tersebut.'
            ], 400);
        }

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        
        $days  = $start->diffInDays($end) + 1; 

        $totalPrice = $car->daily_rent_price * $days;

        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'car_id'      => $request->car_id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'total_price' => $totalPrice,
            'status'      => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data'    => $booking
        ], 201);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'car', 'payment'])->find($id);

        if (!$booking) return response()->json(['message' => 'Booking not found'], 404);

        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $booking
        ], 200);
    }

    public function destroy($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'Transaksi yang sudah diproses tidak bisa dibatalkan.'], 400);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibatalkan dan dihapus.'
        ], 200);
    }
}