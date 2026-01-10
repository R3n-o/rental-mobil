@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Manajemen Armada</h2>
    <div>
        <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fa-solid fa-tags"></i> Kategori Baru
        </button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
            <i class="fa-solid fa-plus"></i> Tambah Mobil
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Gambar</th>
                        <th>Info Mobil</th>
                        <th>Plat Nomor</th>
                        <th>Harga/Hari</th>
                        <th>Status (Ubah)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cars as $car)
                    <tr>
                        <td>
                            @if(isset($car['image']) && $car['image'])
                                <img src="{{ 'http://127.0.0.1:8000/storage/' . $car['image'] }}" 
                                     class="rounded" width="80" height="50" style="object-fit: cover;">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td>
                            <h6 class="mb-0 fw-bold">{{ $car['name'] }}</h6>
                            <small class="text-muted">{{ $car['brand'] }} - {{ $car['model'] }}</small>
                            @if(isset($car['category']['name']))
                                <br><span class="badge bg-light text-dark border">{{ $car['category']['name'] }}</span>
                             @endif
                        </td>
                        <td><span class="badge bg-dark">{{ $car['plate_number'] }}</span></td>
                        <td class="fw-bold text-success">Rp {{ number_format($car['daily_rent_price'], 0, ',', '.') }}</td>
                        
                        <td>
                            <form action="/admin/cars/{{ $car['id'] }}/status" method="POST">
                                @csrf
                                @method('PATCH')
                                @if($car['is_available'])
                                    <button type="submit" name="is_available" value="0" class="btn btn-sm btn-success rounded-pill" title="Klik untuk set Tidak Tersedia">
                                        <i class="fa-solid fa-check-circle"></i> Tersedia
                                    </button>
                                @else
                                    <button type="submit" name="is_available" value="1" class="btn btn-sm btn-secondary rounded-pill" title="Klik untuk set Tersedia">
                                        <i class="fa-solid fa-ban"></i> Maintenance
                                    </button>
                                @endif
                            </form>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-warning me-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editCarModal"
                                data-id="{{ $car['id'] }}"
                                data-name="{{ $car['name'] }}"
                                data-brand="{{ $car['brand'] }}"
                                data-model="{{ $car['model'] }}"
                                data-plate="{{ $car['plate_number'] }}"
                                data-price="{{ $car['daily_rent_price'] }}"
                                data-category="{{ $car['category_id'] }}">
                                <i class="fa-solid fa-edit"></i>
                            </button>
    
                            <form action="/admin/cars/{{ $car['id'] }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus mobil ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada data mobil.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah Armada Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form action="/admin/cars" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nama Mobil</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Pajero Sport" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Merk (Brand)</label>
                    <input type="text" name="brand" class="form-control" placeholder="Toyota" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun/Model</label>
                    <input type="text" name="model" class="form-control" placeholder="2024" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    @endif
                </select>
                <small class="text-muted">Jika kosong, buat kategori baru terlebih dahulu.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Plat Nomor</label>
                <input type="text" name="plate_number" class="form-control" placeholder="B 1234 CD" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Sewa per Hari (Rp)</label>
                <input type="number" name="daily_rent_price" class="form-control" placeholder="500000" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Mobil</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
                <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Mobil</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/categories" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="name" class="form-control" placeholder="Misal: SUV, Sedan" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCarModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Edit Data Mobil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <form id="editCarForm" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT') 
          
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nama Mobil</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Merk</label>
                    <input type="text" name="brand" id="edit_brand" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun</label>
                    <input type="text" name="model" id="edit_model" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" id="edit_category" class="form-select" required>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Plat Nomor</label>
                <input type="text" name="plate_number" id="edit_plate" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Sewa (Rp)</label>
                <input type="number" name="daily_rent_price" id="edit_price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ganti Foto (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editCarModal = document.getElementById('editCarModal');
        editCarModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('edit_name').value = button.getAttribute('data-name');
            document.getElementById('edit_brand').value = button.getAttribute('data-brand');
            document.getElementById('edit_model').value = button.getAttribute('data-model');
            document.getElementById('edit_plate').value = button.getAttribute('data-plate');
            document.getElementById('edit_price').value = button.getAttribute('data-price');
            document.getElementById('edit_category').value = button.getAttribute('data-category');

            var form = document.getElementById('editCarForm');
            form.action = '/admin/cars/' + button.getAttribute('data-id');
        });
    });
</script>
@endsection