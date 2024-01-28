<?php

namespace App\Repository;

use App\Models\CartItems;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository {


 public function findAll() : CartItems|Collection|array
 {
  return CartItems::where('is_active', true)->get();
 }
 public function findById(int $id) : CartItems
 {
  return CartItems::findOrFail($id);
 }

  public function save(array $cartItems) : CartItems
  {
   return CartItems::create( $cartItems);
  }

  public function update(int $id, int $product_id, int $quantity){
   $item = $this->findById($id);
   $item->product_id = $product_id;
   $item->quantity   = $quantity;
   $item->save();
  }


}