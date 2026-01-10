<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 

class AuthController extends Controller
{

    public function loginForm() {
        return view('auth.login');
    }

    public function registerForm() {
        return view('auth.register');
    }
    public function register(Request $request) {
        
   
        $response = Http::post('http://127.0.0.1:8000/api/register', [
            'name'                  => $request->name,
            'email'                 => $request->email,
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'phone'                 => $request->phone,   
            'address'               => $request->address, 
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
        }

       
        $errorMessage = $response->json()['message'] ?? 'Registrasi gagal.';
        return back()->with('error', $errorMessage)->withInput();
    }


    public function login(Request $request) {
        $response = Http::post('http://127.0.0.1:8000/api/login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        $data = $response->json();

        if ($response->successful()) {
            session([
                'token' => $data['token'],
                'user'  => $data['user'],
                'role'  => $data['user']['role']
            ]);

            if ($data['user']['role'] === 'admin') {
                return redirect('/admin/dashboard');
            }
            return redirect('/');
        }

        return back()->with('error', 'Login Gagal. Cek email/password.');
    }

    public function logout() {
        $token = session('token');
        if ($token) {
            Http::withToken($token)->post('http://127.0.0.1:8000/api/logout');
        }

        session()->flush(); 
        return redirect('/login');
    }
}