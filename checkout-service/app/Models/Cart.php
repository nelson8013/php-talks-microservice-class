<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart";

    protected $fillable = ['totalAmount', 'is_active'];

    public function markAsPaid()
    {
        $this->update(['is_active' => false]);
    }

    public function items() : HasMany
    {
        return $this->hasMany(CartItems::class);
    }
}
