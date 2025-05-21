<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
        'discount_percentage',
        'discount_amount',
        'price_after_discount',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            self::calculateDiscountAndPrice($product);
        });

        static::updating(function ($product) {
            self::calculateDiscountAndPrice($product);
        });
    }

    private static function calculateDiscountAndPrice($product)
    {
        $discount_percentage = $product->discount_percentage ?? 0;
        
        // Pastikan diskon dalam range yang benar (1-100%)
        if ($discount_percentage < 0) {
            $discount_percentage = 0;
        } elseif ($discount_percentage > 100) {
            $discount_percentage = 100;
        }
        
        $product->discount_percentage = $discount_percentage;
        $product->discount_amount = $product->price * ($discount_percentage / 100);
        $product->price_after_discount = $product->price - $product->discount_amount;
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}