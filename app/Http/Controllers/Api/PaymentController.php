<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    
    public function store(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'booking_id'      => 'required|exists:bookings,id',
            'amount'          => 'required|numeric',
            'payment_method'  => 'required|string',
          
            'proof_image'     => 'nullable|image|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

    
        $booking = Booking::find($request->booking_id);
        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        
        $path = null;
        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payments', 'public');
        }

        $isVirtualAccount = str_contains($request->payment_method, 'Virtual');
        $initialStatus = $isVirtualAccount ? 'verified' : 'pending';

        $payment = Payment::create([
            'booking_id'     => $request->booking_id,
            'payment_date'   => now(),
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'proof_image'    => $path, 
            'status'         => $initialStatus
        ]);

     
        if ($initialStatus == 'verified') {
            $booking->update(['status' => 'confirmed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diproses',
            'data'    => $payment
        ], 201);
    }

   
    public function update(Request $request, $id)
    {
     
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized. Admin only.'], 403);
        }

        $payment = Payment::find($id);
        if (!$payment) return response()->json(['message' => 'Payment not found'], 404);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:verified,failed'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

       
        $payment->update(['status' => $request->status]);


        if ($request->status == 'verified') {
            $booking = Booking::find($payment->booking_id);
            $booking->update(['status' => 'confirmed']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran diperbarui',
            'data'    => $payment
        ], 200);
    }
}