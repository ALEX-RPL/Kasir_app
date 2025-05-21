<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembelian #{{ $transaction->invoice_number }} - Toko Grosir Roni Laris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #94703A;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            font-size: 24px;
            color: #94703A;
            margin: 0;
        }
        .invoice-header p {
            margin: 5px 0 0;
            color: #666;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-details div {
            flex: 1;
        }
        .invoice-details h3 {
            font-size: 16px;
            margin: 0 0 10px;
            color: #4A3F35;
        }
        .invoice-details p {
            margin: 0 0 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #F4EBD9;
            color: #4A3F35;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .subtotal {
            background-color: #F4EBD9;
            font-weight: bold;
        }
        .notes {
            padding: 15px;
            background-color: #F4EBD9;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .discount-badge {
            display: inline-block;
            background-color: #7D8C5C;
            color: white;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 12px;
        }
        .benefit-highlight {
            background-color: #7D8C5C;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
        @media print {
            body {
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>Toko Grosir Roni Laris</h1>
            <p>Jl. Berdua Tok Ga Jadian, Kota Saranjana, Negara Malang</p>
            <p>Telp: (021) 1234-5678 | Email: info@roniLC.com</p>
        </div>

        <div class="invoice-details">
            <div>
                <h3>Detail Transaksi</h3>
                <p><strong>Invoice:</strong> {{ $transaction->invoice_number }}</p>
                <p><strong>Tanggal:</strong> {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Customer:</strong> {{ $transaction->customer_name ?: 'Umum' }}</p>
            </div>
            <div class="text-right">
                <h3>Ringkasan Pembayaran</h3>
                <p><strong>Total Harga:</strong> Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                <p><strong>Total Diskon:</strong> Rp {{ number_format($transaction->total_discount, 0, ',', '.') }}</p>
                <p><strong>Total Bayar:</strong> Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Asli</th>
                    <th class="text-center">Diskon</th>
                    <th class="text-right">Harga Diskon</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->product_name }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($detail->discount_percentage > 0)
                        <span class="discount-badge">{{ $detail->discount_percentage }}%</span>
                        @else
                        0%
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($detail->price_after_discount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="subtotal">
                    <td colspan="6" class="text-right">Total:</td>
                    <td class="text-right">Rp {{ number_format($transaction->final_price, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="benefit-highlight">
            Selamat! Anda mendapatkan keuntungan sebesar Rp {{ number_format($transaction->customer_benefit, 0, ',', '.') }} dari pembelian ini!
        </div>

        <div class="notes">
            <p><strong>Catatan:</strong></p>
            <p>Terima kasih telah berbelanja di Toko Grosir Roni Laris. Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</p>
        </div>

        <div class="footer">
            <p>Invoice ini dibuat secara otomatis oleh sistem PT. Ryan Akbar Berjaya.</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="no-print" style="margin-top: 30px; text-align: center;">
            <button onclick="window.print();" style="padding: 10px 20px; background-color: #94703A; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Cetak Nota
            </button>
            <a href="{{ route('transactions.show', $transaction->id) }}" style="padding: 10px 20px; background-color: #4A3F35; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; margin-left: 10px;">
                Kembali
            </a>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto print when page loads
            // window.print();
        }
    </script>
</body>
</html>