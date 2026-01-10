@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card p-4">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Selamat Datang</h3>
                <p class="text-muted">Masuk untuk mulai menyewa mobil</p>
            </div>
            
            <form action="/login" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="******" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">MASUK</button>
            </form>

            <div class="text-center mt-3">
                <small>Belum punya akun? <a href="/register">Daftar disini</a></small>
            </div>
        </div>
    </div>
</div>
@endsection