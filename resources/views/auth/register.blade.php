@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-lg rounded-3">
            <div class="card-body p-5">
                <h3 class="fw-bold text-center mb-4 text-primary">Buat Akun Baru</h3>
                
                <form action="/register" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor HP</label>
                        <input type="text" name="phone" class="form-control" placeholder="08123xxxxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Jl. Mawar No. 1..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 6 karakter" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold">Daftar Sekarang</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <p class="text-muted">Sudah punya akun? <a href="/login" class="text-decoration-none fw-bold">Login disini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection