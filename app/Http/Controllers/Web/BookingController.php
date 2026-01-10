<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{

    public function create($car_id)
    {
        $token = session('token');
        $response = Http::get("http://127.0.0.1:8000/api/cars/{$car_id}");
        
        if ($response->failed()) {
            return back()->with('error', 'Mobil tidak ditemukan.');
        }

        $car = $response->json()['data'];

        return view('bookings.create', compact('car'));
    }

    public function store(Request $request)
    {
        $token = session('token');

        $response = Http::withToken($token)->post('http://127.0.0.1:8000/api/bookings', [
            'car_id'     => $request->car_id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        if ($response->successful()) {
            return redirect('/bookings')->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran.');
        }

        $errorMsg = $response->json()['message'] ?? 'Gagal melakukan booking.';
        return back()->with('error', $errorMsg);
    }

    public function index()
    {
        $token = session('token');

        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/bookings');
        $bookings = $response->json()['data'] ?? [];

        return view('bookings.index', compact('bookings'));
    }

    public function paymentForm($id)
    {
        $token = session('token');

        $response = Http::withToken($token)->get("http://127.0.0.1:8000/api/bookings/{$id}");
        
        if ($response->failed()) {
            return back()->with('error', 'Data booking tidak ditemukan.');
        }

        $booking = $response->json()['data'];

        if ($booking['status'] !== 'pending') {
            return redirect('/bookings')->with('error', 'Transaksi ini tidak butuh pembayaran.');
        }

        return view('bookings.payment', compact('booking'));
    }

   public function processPayment(Request $request)
    {
        $token = session('token');
        $booking_id = $request->booking_id;
        $amount = $request->amount;
        $method = $request->payment_method;

        if (str_contains($method, 'Virtual Account')) {
            
            $response = Http::withToken($token)->post('http://127.0.0.1:8000/api/payments', [
                'booking_id'     => $booking_id,
                'amount'         => $amount,
                'payment_method' => $method,
                'status'         => 'verified', 
                'proof_image'    => null 
            ]);

  
            return redirect('/bookings')->with('success', 'Pembayaran Virtual Account Berhasil! Booking telah dikonfirmasi.');

        } else {

            $request->validate(['proof_image' => 'required|image']);

            $response = Http::withToken($token)
                ->attach(
                    'proof_image', 
                    file_get_contents($request->file('proof_image')), 
                    $request->file('proof_image')->getClientOriginalName()
                )
                ->post('http://127.0.0.1:8000/api/payments', [
                    'booking_id'     => $booking_id,
                    'amount'         => $amount,
                    'payment_method' => $method,
                ]);
            
            return redirect('/bookings')->with('info', 'Bukti terkirim! Menunggu verifikasi Admin.');
        }
    }

   public function storeReview(Request $request)
    {
        $token = session('token');

     
        $response = Http::withToken($token)->post('http://127.0.0.1:8000/api/reviews', [
            'booking_id' => $request->booking_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
        }
        $errorMsg = $response->json()['message'] ?? 'Gagal mengirim ulasan.';
        return back()->with('error', $errorMsg);
    }

    public function destroy($id)
    {
        $token = session('token');
        $response = Http::withToken($token)->delete("http://127.0.0.1:8000/api/bookings/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Gagal membatalkan pesanan. ' . ($response->json()['message'] ?? ''));
    }

}