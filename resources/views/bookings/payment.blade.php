@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-4">
    <a href="/bookings" class="btn btn-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Kembali</a>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4">Metode Pembayaran</h3>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center bg-light">
                    <p class="text-muted mb-1">Total Tagihan</p>
                    <h2 class="fw-bold text-primary">Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</h2>
                </div>
            </div>

            <form id="paymentForm" action="/bookings/payment" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking['id'] }}">
                <input type="hidden" name="amount" value="{{ $booking['total_price'] }}">

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-bold">Pilih Metode Pembayaran</div>
                    <div class="card-body">
                        
                        <div class="form-check mb-3 p-3 border rounded pointer-cursor">
                            <input class="form-check-input" type="radio" name="payment_method" id="bank_bca" value="Transfer BCA" onchange="toggleUpload(true)">
                            <label class="form-check-label fw-bold w-100" for="bank_bca">
                                <i class="fa-solid fa-building-columns text-primary me-2"></i> Transfer Bank BCA (Memerlukan Verifikasi)
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded pointer-cursor">
                            <input class="form-check-input" type="radio" name="payment_method" id="va_bni" value="BNI Virtual Account" onchange="toggleUpload(false)">
                            <label class="form-check-label fw-bold w-100" for="va_bni">
                                <i class="fa-solid fa-bolt text-warning me-2"></i> BNI Virtual Account (Verifikasi Instan)
                            </label>
                        </div>

                    </div>
                </div>

                <div id="payment_info_box" class="alert alert-info d-none">
                    <h5 class="fw-bold" id="bank_name">-</h5>
                    <p class="mb-1">Nomor Tujuan:</p>
                    <h2 class="fw-bold text-dark" id="account_number">...</h2>
                </div>

                <div id="upload_section" class="card border-0 shadow-sm d-none">
                    <div class="card-body">
                        <label class="form-label fw-bold">Upload Bukti Transfer</label>
                        <input type="file" name="proof_image" class="form-control" accept="image/*">
                        <small class="text-muted">Wajib untuk metode Transfer Bank.</small>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-success btn-lg fw-bold" id="payBtn" disabled>
                        KONFIRMASI PEMBAYARAN
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    function toggleUpload(isManual) {
        const uploadSection = document.getElementById('upload_section');
        const infoBox = document.getElementById('payment_info_box');
        const bankName = document.getElementById('bank_name');
        const accNumber = document.getElementById('account_number');
        const btn = document.getElementById('payBtn');

        btn.disabled = false;
        infoBox.classList.remove('d-none');

        if (isManual) {
            uploadSection.classList.remove('d-none'); 
            bankName.innerText = "Bank BCA";
            accNumber.innerText = "8830-9911-2233";
            btn.innerText = "Kirim Bukti Pembayaran";
        } else {
            uploadSection.classList.add('d-none'); 
            bankName.innerText = "BNI Virtual Account";
            accNumber.innerText = "8000{{ $booking['id'] }}{{ rand(100,999) }}"; 
            btn.innerText = "Bayar sekarang";
        }
    }

    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault(); 

        Swal.fire({
            title: 'Memproses Pembayaran...',
            html: 'Mohon tunggu sebentar',
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
            }
        }).then((result) => {
            e.target.submit();
        })
    });
</script>
@endsection