@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Transaksi #{{ $transaction->invoice_number }}</h5>
                <div>
                    <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print me-1"></i> Cetak Nota
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Informasi Transaksi</h6>
                        <p class="mb-1"><strong>No. Invoice:</strong> {{ $transaction->invoice_number }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Nama Customer:</strong> {{ $transaction->customer_name ?: 'Umum' }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="fw-bold">Ringkasan Pembayaran</h6>
                        <p class="mb-1"><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Total Diskon:</strong> Rp {{ number_format($transaction->total_discount, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Total Bayar:</strong> Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</p>
                        <p class="mb-1 text-success"><strong>Keuntungan Customer:</strong> Rp {{ number_format($transaction->customer_benefit, 0, ',', '.') }}</p>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Detail Produk</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Asli</th>
                                <th class="text-center">Diskon</th>
                                <th class="text-end">Harga Diskon</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->product_name }}</td>
                                <td class="text-center">{{ $detail->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($detail->discount_percentage > 0)
                                    <span class="badge bg-success">{{ $detail->discount_percentage }}%</span>
                                    @else
                                    <span class="badge bg-secondary">0%</span>
                                    @endif
                                </td>
                                <td class="text-end">Rp {{ number_format($detail->price_after_discount, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th colspan="6" class="text-end">Total:</th>
                                <th class="text-end">Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h6 class="fw-bold mb-2">Informasi Keuntungan</h6>
                            <p class="mb-0">
                                Customer mendapatkan keuntungan sebesar <strong>Rp {{ number_format($transaction->customer_benefit, 0, ',', '.') }}</strong> dari transaksi ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection