<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('final_price');
        $totalSavings = Transaction::sum('customer_benefit');
        
        // Transaksi terbaru
        $latestTransactions = Transaction::with('details')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Produk dengan stok rendah
        $lowStockProductsList = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();
            
        return view('dashboard', compact(
            'totalProducts', 
            'lowStockProducts', 
            'totalTransactions', 
            'totalRevenue',
            'totalSavings',
            'latestTransactions',
            'lowStockProductsList'
        ));
    }
}