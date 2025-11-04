<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'client_id', 'gateway_id', 'external_id', 'status', 'amount', 'card_last_numbers'
    ];

    public function products() {
        return $this->belongsToMany(Product::class, 'transaction_products')
                    ->withPivot('quantity');
    }
}
