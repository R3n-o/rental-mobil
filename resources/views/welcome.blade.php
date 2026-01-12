@extends('layouts.app')

@section('title', 'Sewa Mobil Termurah')

@section('content')
<div class="p-5 mb-4 bg-primary rounded-3 text-white shadow"
     style="background: linear-gradient(45deg, #4e73df, #224abe);">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Jelajahi Perjalananmu</h1>
        <p class="col-md-8 fs-4">Sewa mobil berkualitas dengan harga terbaik. Proses cepat, mudah, dan aman. Pilih mobil yang pas untukmu sekarang!</p>
        @if(!session()->has('token'))
            <a href="/register" class="btn btn-light btn-lg fw-bold text-primary">Daftar Sekarang</a>
        @else
            <a href="#katalog" class="btn btn-light btn-lg fw-bold text-primary">Lihat Mobil</a>
        @endif
    </div>
</div>

<div id="katalog" class="mb-5">
    <h3 class="fw-bold mb-4 border-start border-4 border-primary ps-3">Armada Tersedia</h3>

    <div class="d-flex justify-content-center mb-5 gap-2 flex-wrap">
    <a href="/" class="btn {{ request('category_id') ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill px-4">
        Semua Mobil
    </a>
    @foreach($categories as $cat)
        <a href="/?category_id={{ $cat['id'] }}"
           class="btn {{ request('category_id') == $cat['id'] ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4">
            {{ $cat['name'] }}
        </a>
    @endforeach
</div>

    <div class="row">
        @forelse($cars as $car)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-effect">
                <div style="height: 200px; overflow: hidden; background-color: #eee;">
                    @if(isset($car['image']) && $car['image'])
                        <img src="{{ config('app.url') . '/storage/' . $car['image'] }}"
                             class="card-img-top w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="d-flex justify-content-center align-items-center h-100 text-muted">
                            <i class="fa-solid fa-car fa-3x"></i>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="card-title fw-bold mb-1">{{ $car['name'] }}</h5>
                            <small class="text-muted">{{ $car['brand'] }} - {{ $car['model'] }}</small>
                        </div>
                        <span class="badge bg-dark">{{ $car['plate_number'] }}</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Harga Sewa</small>
                            <span class="fw-bold text-primary fs-5">Rp {{ number_format($car['daily_rent_price'], 0, ',', '.') }}</span>
                            <small>/hari</small>
                        </div>

                        @if($car['is_available'])
                            <span class="badge bg-success rounded-pill px-3">Tersedia</span>
                        @else
                            <span class="badge bg-danger rounded-pill px-3">Sedang Disewa</span>
                        @endif
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pb-3">
                    @if($car['is_available'])
                        <a href="/bookings/create/{{ $car['id'] }}" class="btn btn-primary w-100 fw-bold">
                            <i class="fa-solid fa-key"></i> Sewa Sekarang
                        </a>
                    @else
                        <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" width="200" alt="Empty">
            <h4 class="text-muted mt-3">Belum ada armada tersedia.</h4>
        </div>
        @endforelse
    </div>
</div>
@endsection
