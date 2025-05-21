@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="mb-3">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-box me-1"></i> Detail Produk</span>
                <div>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary me-1">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <h3 class="card-title mb-4">{{ $product->name }}</h3>
                
                <div class="mb-3">
                    <h5>Deskripsi:</h5>
                    <p class="card-text">{{ $product->description ?: 'Tidak ada deskripsi' }}</p>
                </div>
                
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%">Stock:</th>
                        <td>
                            @if($product->stock <= 5)
                                <span class="badge rounded-pill bg-danger">{{ $product->stock }}</span>
                            @elseif($product->stock <= 10)
                                <span class="badge rounded-pill bg-warning text-dark">{{ $product->stock }}</span>
                            @else
                                <span class="badge rounded-pill bg-info text-dark">{{ $product->stock }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Harga Asli:</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Diskon:</th>
                        <td>
                            <span class="badge rounded-pill 
                                @if($product->discount_percentage > 0) bg-success @else bg-secondary @endif">
                                {{ $product->discount_percentage }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Nilai Diskon:</th>
                        <td>Rp {{ number_format($product->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Setelah Diskon:</th>
                        <td>
                            <span class="fw-bold">Rp {{ number_format($product->price_after_discount, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                </table>
                
                <div class="mt-4">
                    <h5>Informasi Tambahan:</h5>
                    <p><small class="text-muted">Dibuat pada: {{ $product->created_at->format('d F Y, H:i') }}</small></p>
                    <p><small class="text-muted">Terakhir diperbarui: {{ $product->updated_at->format('d F Y, H:i') }}</small></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calculator me-1"></i> Simulasi Pembelian
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="simulationQty" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="simulationQty" min="1" value="1" max="{{ $product->stock }}">
                </div>
                
                <table class="table table-bordered mt-4">
                    <tr>
                        <th>Harga Satuan:</th>
                        <td>Rp <span id="unitPrice">{{ number_format($product->price, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <th>Diskon per Unit:</th>
                        <td>Rp <span id="unitDiscount">{{ number_format($product->discount_amount, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr>
                        <th>Harga Setelah Diskon:</th>
                        <td>Rp <span id="discountedPrice">{{ number_format($product->price_after_discount, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr class="table-success">
                        <th>Total Harga:</th>
                        <td class="fw-bold">Rp <span id="totalPrice">{{ number_format($product->price_after_discount, 0, ',', '.') }}</span></td>
                    </tr>
                    <tr class="table-info">
                        <th>Total Keuntungan Customer:</th>
                        <td class="fw-bold">Rp <span id="totalSavings">{{ number_format($product->discount_amount, 0, ',', '.') }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const simulationQty = document.getElementById('simulationQty');
        const unitPrice = {{ $product->price }};
        const discountAmount = {{ $product->discount_amount }};
        const priceAfterDiscount = {{ $product->price_after_discount }};
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
        
        function updateCalculation() {
            const qty = parseInt(simulationQty.value) || 1;
            
            // Update total price
            const totalPrice = priceAfterDiscount * qty;
            document.getElementById('totalPrice').textContent = formatCurrency(totalPrice);
            
            // Update total savings
            const totalSavings = discountAmount * qty;
            document.getElementById('totalSavings').textContent = formatCurrency(totalSavings);
        }
        
        simulationQty.addEventListener('input', updateCalculation);
        updateCalculation();
    });
</script>
@endsection