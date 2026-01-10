@extends('layouts.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Data Transaksi Sewa</h2>
    <span class="badge bg-primary fs-6">{{ count($bookings) }} Transaksi</span>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Penyewa</th>
                        <th>Mobil</th>
                        <th>Jadwal</th>
                        <th>Total</th>
                        <th>Status Booking</th>
                        <th>Aksi Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td>#{{ $booking['id'] }}</td>
                        <td>
                            <div class="fw-bold">{{ $booking['user']['name'] ?? 'User Hapus' }}</div>
                            <small class="text-muted">{{ $booking['user']['email'] ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $booking['car']['name'] ?? 'Mobil Hapus' }}</div>
                            <small class="text-muted">{{ $booking['car']['plate_number'] ?? '-' }}</small>
                        </td>
                        <td>
                            <small class="d-block text-nowrap">{{ $booking['start_date'] }}</small>
                            <small class="d-block text-nowrap">s/d {{ $booking['end_date'] }}</small>
                        </td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($booking['total_price'], 0, ',', '.') }}
                        </td>
                        <td>
                            @if($booking['status'] == 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif($booking['status'] == 'confirmed')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($booking['status'] == 'finished')
                                <span class="badge bg-secondary">Selesai</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                             @if(isset($booking['payment']) && $booking['payment']['status'] == 'pending')
                                <button class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#verifyModal"
                                    data-id="{{ $booking['payment']['id'] }}"
                                    data-image="{{ 'http://127.0.0.1:8000/storage/' . $booking['payment']['proof_image'] }}"
                                    data-amount="{{ $booking['payment']['amount'] }}"
                                    data-method="{{ $booking['payment']['payment_method'] }}">
                                    <i class="fa-solid fa-eye"></i> Cek Bukti
                                </button>
                             @elseif($booking['status'] == 'confirmed')
                                <span class="text-success fw-bold"><i class="fa-solid fa-check-double"></i> Lunas</span>
                             @else
                                <small class="text-muted">Belum bayar</small>
                             @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="verifyModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Verifikasi Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-1 text-muted">Metode Pembayaran:</p>
        <h5 class="fw-bold" id="pay_method">-</h5>
        
        <p class="mb-1 text-muted">Nominal:</p>
        <h4 class="fw-bold text-success" id="pay_amount">Rp 0</h4>
        
        <div class="mt-3 mb-3 border p-2 rounded">
            <img src="" id="proof_img" class="img-fluid" alt="Bukti Transfer">
        </div>

        <p>Apakah bukti transfer ini valid?</p>

        <form id="verifyForm" method="POST" class="d-flex gap-2 justify-content-center">
            @csrf
            @method('PUT')
            
            <button type="submit" name="action" value="failed" class="btn btn-outline-danger">
                <i class="fa-solid fa-times"></i> Tolak (Palsu)
            </button>
            
            <button type="submit" name="action" value="verified" class="btn btn-success">
                <i class="fa-solid fa-check"></i> Terima (Valid)
            </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var verifyModal = document.getElementById('verifyModal');
        verifyModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var image = button.getAttribute('data-image');
            var amount = button.getAttribute('data-amount');
            var method = button.getAttribute('data-method');

            document.getElementById('proof_img').src = image;
            document.getElementById('pay_method').innerText = method;
            document.getElementById('pay_amount').innerText = "Rp " + new Intl.NumberFormat('id-ID').format(amount);

            var form = document.getElementById('verifyForm');
            form.action = '/admin/payments/' + id;
        });
    });
</script>
@endsection