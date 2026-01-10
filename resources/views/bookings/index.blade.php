@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<h2 class="fw-bold mb-4">Riwayat Penyewaan Saya</h2>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    @forelse($bookings as $booking)
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold text-primary">{{ $booking['car']['name'] }}</h5>

                    {{-- LOGIKA STATUS LABEL --}}
                    @if($booking['status'] == 'pending')
                        @if(isset($booking['payment']) && $booking['payment']['status'] == 'pending')
                            <span class="badge bg-info text-dark">Sedang Diverifikasi</span>
                        @else
                            <span class="badge bg-warning text-dark">Belum Dibayar</span>
                        @endif
                    @elseif($booking['status'] == 'confirmed')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($booking['status'] == 'finished')
                        <span class="badge bg-secondary">Selesai</span>
                    @else
                        <span class="badge bg-secondary">{{ $booking['status'] }}</span>
                    @endif
                </div>
                
                <p class="mb-1"><i class="fa-solid fa-calendar"></i> {{ $booking['start_date'] }} s/d {{ $booking['end_date'] }}</p>
                <p class="mb-1"><i class="fa-solid fa-tag"></i> Total: <strong>Rp {{ number_format($booking['total_price'], 0, ',', '.') }}</strong></p>
                
                <hr>

                {{-- LOGIKA TOMBOL --}}
                @if($booking['status'] == 'pending')
                    
                    {{-- KONDISI 1: Sudah upload bukti, tinggal tunggu admin --}}
                    @if(isset($booking['payment']) && $booking['payment']['status'] == 'pending')
                        <div class="alert alert-light border text-center py-2 mb-0">
                            <i class="fa-solid fa-hourglass-half text-primary mb-1 d-block" style="font-size: 1.5rem"></i>
                            <small class="text-muted fw-bold">Bukti Transfer Terkirim</small><br>
                            <span style="font-size: 0.8rem">Menunggu konfirmasi Admin.</span>
                        </div>

                    {{-- KONDISI 2: Belum upload bukti sama sekali --}}
                    @else
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="/bookings/payment/{{ $booking['id'] }}" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-credit-card"></i> Bayar
                                </a>
                            </div>
                            <div class="col-6">
                                <form action="{{ route('bookings.destroy', $booking['id']) }}" method="POST" class="w-100" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fa-solid fa-trash"></i> Batal
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                @else
                    {{-- Tombol untuk status Confirmed / Finished --}}
                    <button class="btn btn-outline-success w-100 mb-2" disabled>
                        <i class="fa-solid fa-check"></i> Lunas / Selesai
                    </button>

                    {{-- UPDATE PENTING: Mengirim data-bookingid, bukan data-carid --}}
                    <button class="btn btn-warning w-100 fw-bold text-white" 
                        data-bs-toggle="modal" 
                        data-bs-target="#reviewModal"
                        data-bookingid="{{ $booking['id'] }}" 
                        data-carname="{{ $booking['car']['name'] }}">
                        <i class="fa-solid fa-pen-to-square"></i> Beri Ulasan
                    </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">Anda belum pernah menyewa mobil.</p>
        <a href="/" class="btn btn-primary">Cari Mobil</a>
    </div>
    @endforelse
</div>

<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tulis Ulasan Pengalaman Anda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/reviews" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- UPDATE PENTING: Name diganti jadi booking_id --}}
                    <input type="hidden" name="booking_id" id="review_booking_id">
                    
                    <div class="alert alert-light border">
                        <small class="text-muted">Mobil:</small>
                        <h6 class="fw-bold mb-0" id="review_car_name">-</h6>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Rating Bintang</label>
                        <select name="rating" class="form-select" required>
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                            <option value="3">⭐⭐⭐ (Cukup)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Komentar</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan kondisi mobil dan pelayanan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var reviewModal = document.getElementById('reviewModal');
        reviewModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
        
            var bookingId = button.getAttribute('data-bookingid');
            var carName = button.getAttribute('data-carname');

            document.getElementById('review_booking_id').value = bookingId;
            document.getElementById('review_car_name').innerText = carName;
        });
    });
</script>
@endsection