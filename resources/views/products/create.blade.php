@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-plus-circle me-2"></i> Tambah Produk Baru
    </h2>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', 0) }}" min="0" step="100" required>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6 mb-3">
                    <label for="discount_percentage" class="form-label">Diskon (%)</label>
                    <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', 0) }}" min="0" max="100">
                    @error('discount_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informasi Diskon</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <p class="mb-1">Harga Asli: <span id="original_price_display">Rp0</span></p>
                            </div>
                            <div class="mb-2">
                                <p class="mb-1">Nominal Diskon: <span id="discount_amount_display">Rp0</span></p>
                            </div>
                            <div>
                                <p class="mb-1">Harga Setelah Diskon: <span id="final_price_display">Rp0</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fungsi untuk menghitung dan menampilkan informasi diskon
    function calculateDiscount() {
        const price = parseFloat($('#price').val()) || 0;
        const discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
        
        const discountAmount = price * (discountPercentage / 100);
        const finalPrice = price - discountAmount;
        
        // Format ke Rupiah
        $('#original_price_display').text('Rp' + price.toLocaleString('id-ID'));
        $('#discount_amount_display').text('Rp' + discountAmount.toLocaleString('id-ID'));
        $('#final_price_display').text('Rp' + finalPrice.toLocaleString('id-ID'));
    }
    
    // Event listener untuk perubahan harga atau diskon
    $('#price, #discount_percentage').on('input', function() {
        calculateDiscount();
    });
    
    // Panggil fungsi saat halaman dimuat untuk inisialisasi tampilan
    $(document).ready(function() {
        calculateDiscount();
    });
</script>
@endsection