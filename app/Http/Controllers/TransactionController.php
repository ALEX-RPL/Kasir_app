<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $invoiceNumber = Transaction::generateInvoiceNumber();
        return view('transactions.create', compact('products', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $totalPrice = 0;
            $totalDiscount = 0;
            $finalPrice = 0;
            $customerBenefit = 0;
            
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'customer_name' => $request->customer_name,
                'total_price' => 0,
                'total_discount' => 0,
                'final_price' => 0,
                'customer_benefit' => 0,
            ]);

            foreach ($request->product_ids as $key => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = $request->quantities[$key];
                
                if ($product->stock < $quantity) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi");
                }
                
                $product->stock -= $quantity;
                $product->save();
                
                $subtotal = $product->price_after_discount * $quantity;
                
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'discount_percentage' => $product->discount_percentage,
                    'discount_amount' => $product->discount_amount,
                    'price_after_discount' => $product->price_after_discount,
                    'subtotal' => $subtotal,
                ]);
                
                $totalPrice += $product->price * $quantity;
                $totalDiscount += $product->discount_amount * $quantity;
                $finalPrice += $subtotal;
                $customerBenefit += $product->discount_amount * $quantity;
            }
            
            $transaction->update([
                'total_price' => $totalPrice,
                'total_discount' => $totalDiscount,
                'final_price' => $finalPrice,
                'customer_benefit' => $customerBenefit,
            ]);
            
            DB::commit();
            
            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Transaksi berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('details.product');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Print invoice for the transaction.
     */
    public function printInvoice(Transaction $transaction)
    {
        $transaction->load('details.product');
        return view('transactions.print', compact('transaction'));
    }
}