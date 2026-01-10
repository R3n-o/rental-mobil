@extends('layouts.app')

@section('title', 'Booking Mobil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h3 class="fw-bold mb-4">Konfirmasi Penyewaan</h3>
        
        <div class="card border-0 shadow-lg overflow-hidden mb-4">
            <div class="row g-0">
                <div class="col-md-5 bg-light p-4 text-center d-flex flex-column align-items-center justify-content-center">
                    @if(isset($car['image']) && $car['image'])
                        <img src="{{ 'http://127.0.0.1:8000/storage/' . $car['image'] }}" 
                             class="img-fluid rounded mb-3 shadow-sm" style="max-height: 200px;">
                    @endif
                    <h4 class="fw-bold">{{ $car['name'] }}</h4>
                    <p class="text-muted mb-0">{{ $car['brand'] }} - {{ $car['model'] }}</p>
                    <div class="mt-3 badge bg-primary fs-6">
                        Rp {{ number_format($car['daily_rent_price'], 0, ',', '.') }} / hari
                    </div>
                </div>

                <div class="col-md-7 p-4 bg-white">
                    <form action="/bookings" method="POST">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car['id'] }}">
                        
                        <input type="hidden" id="price_per_day" value="{{ $car['daily_rent_price'] }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai Sewa</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Selesai</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <div class="alert alert-info mt-4">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Estimasi:</span>
                                <span id="total_price" class="fs-5">Rp 0</span>
                            </div>
                            <small class="text-muted">*Harga dihitung berdasarkan jumlah hari.</small>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">
                                <i class="fa-solid fa-check-circle"></i> AJUKAN SEWA
                            </button>
                            <a href="/" class="btn btn-outline-secondary">Batal & Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fa-solid fa-star text-warning"></i> Ulasan Pelanggan
            </div>
            <div class="card-body">
                @if(isset($car['reviews']) && count($car['reviews']) > 0)
                    @foreach($car['reviews'] as $review)
                        <div class="mb-3 border-bottom pb-2">
                            <div class="d-flex justify-content-between">
                                <h6 class="fw-bold mb-0">{{ $review['user']['name'] ?? 'Pelanggan' }}</h6>
                                <small class="text-muted">{{ date('d M Y', strtotime($review['created_at'])) }}</small>
                            </div>
                            <div class="text-warning mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review['rating'] ? '' : 'text-secondary opacity-25' }}"></i>
                                @endfor
                            </div>
                            <p class="text-muted mb-0">"{{ $review['comment'] }}"</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fa-regular fa-comment-dots fa-2x mb-2"></i>
                        <p>Belum ada ulasan untuk mobil ini.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const totalLabel = document.getElementById('total_price');
    const pricePerDay = document.getElementById('price_per_day').value;

    function calculateTotal() {
        if(startInput.value && endInput.value) {
            const start = new Date(startInput.value);
            const end = new Date(endInput.value);
            
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 

            if(diffDays > 0) {
                const total = diffDays * pricePerDay;
                totalLabel.innerHTML = "Rp " + new Intl.NumberFormat('id-ID').format(total);
            } else {
                totalLabel.innerHTML = "Rp 0 (Tanggal Invalid)";
            }
        }
    }

    startInput.addEventListener('change', calculateTotal);
    endInput.addEventListener('change', calculateTotal);
</script>
@endsection