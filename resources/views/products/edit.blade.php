@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Edit Produk</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stok</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Harga (Rp)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="discount_percentage" class="form-label">Diskon (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('discount_percentage') is-invalid @enderror" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage', $product->discount_percentage) }}" min="0" max="100" required>
                                    <span class="input-group-text">%</span>
                                    @error('discount_percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Perhitungan Harga</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1">Harga Asli:</p>
                                            <h5>Rp <span id="original-price">{{ number_format($product->price, 0, ',', '.') }}</span></h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1">Diskon:</p>
                                            <h5>Rp <span id="discount-amount">{{ number_format($product->discount_amount, 0, ',', '.') }}</span></h5>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1">Harga Setelah Diskon:</p>
                                            <h5>Rp <span id="final-price">{{ number_format($product->price_after_discount, 0, ',', '.') }}</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Menghitung ulang harga saat ada perubahan pada harga atau diskon
        $('#price, #discount_percentage').on('input', function() {
            calculatePrice();
        });
        
        function calculatePrice() {
            let originalPrice = parseFloat($('#price').val()) || 0;
            let discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
            
            // Batasi diskon antara 0-100%
            if (discountPercentage < 0) discountPercentage = 0;
            if (discountPercentage > 100) discountPercentage = 100;
            
            let discountAmount = originalPrice * (discountPercentage / 100);
            let finalPrice = originalPrice - discountAmount;
            
            // Update tampilan
            $('#original-price').text(formatRupiah(originalPrice));
            $('#discount-amount').text(formatRupiah(discountAmount));
            $('#final-price').text(formatRupiah(finalPrice));
        }
        
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }
    });
</script>
@endsection