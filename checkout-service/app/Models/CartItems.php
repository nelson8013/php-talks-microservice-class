<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItems extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id' ,'quantity', 'price'];


    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

}
