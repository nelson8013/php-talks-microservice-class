<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = "inventory";
    
    protected $fillable = [
        'product_id',
        'quantity',
        'is_available'
    ];
}
