@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-list-alt me-2"></i>Daftar Transaksi</h1>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Transaksi Baru
    </a>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-table me-1"></i> Data Transaksi
    </div>
    <div class="card-body">
        @if($transactions->isEmpty())
            <div class="alert alert-info">
                Belum ada data transaksi. Silakan buat transaksi baru.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Tanggal</th>
                            <th>Customer</th>
                            <th>Total Harga</th>
                            <th>Total Diskon</th>
                            <th>Harga Akhir</th>
                            <th>Keuntungan Customer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->invoice_number }}</td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $transaction->customer_name ?? 'Umum' }}</td>
                                <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->total_discount, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($transaction->customer_benefit, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection