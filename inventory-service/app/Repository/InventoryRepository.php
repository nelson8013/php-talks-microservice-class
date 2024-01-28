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

 public function findById(int $id) : Inventory
 {
   return Inventory::findOrFail($id);
 }

 public function findByProductId(int $id) : Inventory
 {
   return Inventory::where('product_id', $id)->first();
 }

 public function existsByProductId(int $id) : bool
 {
   return Inventory::where('product_id', $id)->exists();
 }


 public function findAll() : Inventory|Collection|array
 {
  return Inventory::all();
 }

 public function updateProductInventory(int $productId,  int $quantity) : void
 {
  $inventory = $this->findByProductId($productId);
  $inventory->quantity = $quantity;
  $inventory->save();
 }
}