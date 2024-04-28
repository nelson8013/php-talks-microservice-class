<?php

namespace App\Repository;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;



class InventoryRepository
{

 public function save(array $inventory) : Inventory|bool
 {
   return Inventory::create($inventory);
 }

 public function findProductQuantity(int $productId) : int
 {
   return Inventory::select('quantity')->where('product_id', $productId)->first()->quantity;
 }
 
 public function updateProductInventory(int $productId,  int $quantity) : void
 {
  $inventory = $this->findByProductId($productId);
  $inventory->quantity = $quantity;
  $inventory->save();
 }
 public function existsByProductId(int $id) : bool
 {
   return Inventory::where('product_id', $id)->exists();
 }


}