@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="mb-4">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </h2>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x mb-2" style="color: var(--primary-color);"></i>
                <h5 class="card-title">Total Produk</h5>
                <h2 class="mb-0">{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2" style="color: var(--danger-color);"></i>
                <h5 class="card-title">Stok Rendah</h5>
                <h2 class="mb-0">{{ $lowStockProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-2x mb-2" style="color: var(--success-color);"></i>
                <h5 class="card-title">Total Transaksi</h5>
                <h2 class="mb-0">{{ $totalTransactions }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <i class="fas fa-money-bill-wave fa-2x mb-2" style="color: var(--accent-color);"></i>
                <h5 class="card-title">Total Pendapatan</h5>
                <h2 class="mb-0">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Transactions -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Transaksi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($latestTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestTransactions as $transaction)
                                <tr>
                                    <td>
                                        <a href="{{ route('transactions.show', $transaction->id) }}">
                                            {{ $transaction->invoice_number }}
                                        </a>
                                    </td>
                                    <td>{{ $transaction->customer_name ?: 'Umum' }}</td>
                                    <td>Rp{{ number_format($transaction->final_price, 0, ',', '.') }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('transactions.index') }}" class="btn btn-primary btn-sm">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="mb-0">Belum ada transaksi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i> Produk Stok Rendah
                </h5>
            </div>
            <div class="card-body">
                @if($lowStockProductsList->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProductsList as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-danger">{{ $product->stock }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="mb-0">Tidak ada produk dengan stok rendah</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Savings -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-piggy-bank me-2"></i> Total Keuntungan Customer
                </h5>
            </div>
            <div class="card-body text-center">
                <h3 class="text-success">Rp{{ number_format($totalSavings, 0, ',', '.') }}</h3>
                <p class="mb-0">Total keuntungan yang diperoleh customer dari diskon</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i> Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-box-open me-2"></i> Tambah Produk Baru
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('transactions.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-cash-register me-2"></i> Buat Transaksi Baru
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg w-100">
                            <i class="fas fa-list-alt me-2"></i> Kelola Inventory
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection