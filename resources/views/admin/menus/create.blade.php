@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-plus me-2"></i>Tambah Menu Baru</h4>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Error Validasi:</h6>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Nama Menu -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Contoh: Nasi Goreng Spesial" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Deskripsi lengkap menu..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Maksimal 500 karakter</div>
                            </div>

                            <!-- Harga dan Kategori -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" 
                                                   min="1000" max="1000000" step="500"
                                                   placeholder="25000" required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Min: Rp 1.000 - Max: Rp 1.000.000</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category') is-invalid @enderror" 
                                                id="category" name="category" required>
                                            <option value="">Pilih Kategori</option>
                                            <option value="makanan" {{ old('category') == 'makanan' ? 'selected' : '' }}>Makanan</option>
                                            <option value="minuman" {{ old('category') == 'minuman' ? 'selected' : '' }}>Minuman</option>
                                            <option value="snack" {{ old('category') == 'snack' ? 'selected' : '' }}>Snack</option>
                                            <option value="dessert" {{ old('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Upload Gambar -->
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar Menu <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.
                                </div>
                                
                                <!-- Image Preview -->
                                <div class="mt-3 text-center">
                                    <img id="imagePreview" src="#" alt="Preview" 
                                         class="img-fluid rounded border" style="max-height: 200px; display: none;">
                                    <div id="noImagePreview" class="text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Preview gambar akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Menu Aktif/Tersedia</strong>
                                    </label>
                                </div>
                                <div class="form-text">
                                    Jika dimatikan, menu akan berstatus "Habis"
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Menu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const noPreview = document.getElementById('noImagePreview');
    const file = e.target.files[0];
    
    if (file) {
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
            this.value = ''; // Clear the file input
            preview.style.display = 'none';
            noPreview.style.display = 'block';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Format gambar tidak didukung! Gunakan JPEG, PNG, JPG, atau GIF.');
            this.value = ''; // Clear the file input
            preview.style.display = 'none';
            noPreview.style.display = 'block';
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            noPreview.style.display = 'none';
        }
        
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        noPreview.style.display = 'block';
    }
});

// Form validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    const price = document.getElementById('price').value;
    const image = document.getElementById('image').value;
    
    if (price < 1000 || price > 1000000) {
        e.preventDefault();
        alert('Harga harus antara Rp 1.000 hingga Rp 1.000.000');
        return false;
    }
    
    if (!image) {
        e.preventDefault();
        alert('Gambar menu wajib diupload!');
        return false;
    }
});
</script>

<style>
.form-text {
    font-size: 0.8rem;
    color: #6c757d;
}
.invalid-feedback {
    display: block;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}
#noImagePreview {
    padding: 2rem;
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
}
</style>
@endsection