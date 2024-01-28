<?php

namespace App\Repository;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository {


 public function findAll() : Cart|Collection|array
 {
  return Cart::where('is_active', true)->get();
 }
 public function findById(int $id) : Cart
 {
  return Cart::findOrFail($id);
 }

 public function existsById(int $id) : bool
 {
  return Cart::where('id', $id)->first()->exists();
 }

  public function save(array $cart) : Cart
  {
   return Cart::create( $cart);
  }


}