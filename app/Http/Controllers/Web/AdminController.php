<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function dashboard()
    {
        $token = session('token');
        
        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/cars');
        $cars = $response->json()['data'] ?? [];
        
        $catResponse = Http::get('http://127.0.0.1:8000/api/categories');
        $categories = $catResponse->json()['data'] ?? [];

        return view('admin.dashboard', compact('cars', 'categories'));
    }

    public function storeCar(Request $request)
    {
        $token = session('token');

        $response = Http::withToken($token)
            ->attach(
                'image', 
                file_get_contents($request->file('image')), 
                $request->file('image')->getClientOriginalName()
            )
            ->post('http://127.0.0.1:8000/api/cars', [
                'name'             => $request->name,
                'brand'            => $request->brand,
                'model'            => $request->model,
                'plate_number'     => $request->plate_number,
                'daily_rent_price' => $request->daily_rent_price,
                'category_id'      => $request->category_id,
            ]);

        if ($response->successful()) {
            return back()->with('success', 'Mobil berhasil ditambahkan!');
        }

        return back()->with('error', 'Gagal menambah mobil.');
    }

    public function updateCar(Request $request, $id)
    {
        $token = session('token');

        $data = [
            'name'             => $request->name,
            'brand'            => $request->brand,
            'model'            => $request->model,
            'plate_number'     => $request->plate_number,
            'daily_rent_price' => $request->daily_rent_price,
            'category_id'      => $request->category_id,
            '_method'          => 'PUT',
        ];

        $http = Http::withToken($token);

        if ($request->hasFile('image')) {
            $response = $http->attach(
                'image', 
                file_get_contents($request->file('image')), 
                $request->file('image')->getClientOriginalName()
            )->post("http://127.0.0.1:8000/api/cars/{$id}", $data);
        } else {
            $response = $http->asForm()->post("http://127.0.0.1:8000/api/cars/{$id}", $data);
        }

        if ($response->successful()) {
            return back()->with('success', 'Data mobil berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal update: ' . $response->body());
    }


    public function destroyCar($id)
    {
        $token = session('token');
        $response = Http::withToken($token)->delete("http://127.0.0.1:8000/api/cars/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'Mobil berhasil dihapus!');
        }
        return back()->with('error', 'Gagal menghapus mobil.');
    }

    public function bookings()
    {
        $token = session('token');
        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/bookings');
        $bookings = $response->json()['data'] ?? [];

        return view('admin.bookings', compact('bookings'));
    }

    public function verifyPayment(Request $request, $id)
    {
        $token = session('token');
        $response = Http::withToken($token)->put("http://127.0.0.1:8000/api/payments/{$id}", [
            'status' => $request->action
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Status pembayaran berhasil diperbarui!');
        }
        return back()->with('error', 'Gagal memverifikasi pembayaran.');
    }


    public function storeCategory(Request $request)
    {
        $token = session('token');
        
        $response = Http::withToken($token)->post('http://127.0.0.1:8000/api/categories', [
            'name' => $request->name
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Kategori baru berhasil ditambahkan!');
        }
        return back()->with('error', 'Gagal menambah kategori.');
    }

    public function updateCarStatus($id, Request $request)
    {
        $token = session('token');
        
        $response = Http::withToken($token)->put("http://127.0.0.1:8000/api/cars/{$id}", [
            'is_available' => $request->is_available,
            '_method' => 'PUT'
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Status mobil diperbarui!');
        }
        return back()->with('error', 'Gagal update status.');
    }
}