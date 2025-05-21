<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'total_price',
        'total_discount',
        'final_price',
        'customer_benefit',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Menghasilkan nomor invoice secara otomatis
    public static function generateInvoiceNumber()
    {
        $lastTransaction = self::orderBy('id', 'desc')->first();
        $currentDate = date('Ymd');
        
        if (!$lastTransaction) {
            return 'INV-' . $currentDate . '-0001';
        }
        
        $lastInvoiceNumber = $lastTransaction->invoice_number;
        $lastNumber = intval(substr($lastInvoiceNumber, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return 'INV-' . $currentDate . '-' . $newNumber;
    }
}