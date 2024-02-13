<?php

namespace App\Repository;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\ProductRequest;




class ProductRepository
{

 public function save(array $product) : Product
 {
   return Product::create($product);
 }

 public function update(int $id, ProductRequest $request) : bool
 {
   $product = $this->findById($id);
   return $product->update($request->all());
 }

 public function findById(int $id) : Product
 {
   return Product::findOrFail($id);
 }

 public function findPrice(int $id) : float
 {
   return Product::select('price')->where('id',$id)->first()->price;
 }

 public function existsById(int $id) : bool
 {
  return Product::where('id', $id)->exists();
 }

 public function findAll() : Product|Collection|array
 {
  return Product::all();
 }
}