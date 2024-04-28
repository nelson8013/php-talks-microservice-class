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

 public function update(int $id, ProductRequest|array $request) : bool | Product
 {
    $product = $this->findById($id);
    $product->update($request->all());
    return $product;
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

 public function delete(int $id) :  ?Product
 {
  $product = $this->findById($id);

  if ($product) {
    $deleted = $product->delete();
    if($deleted) return $product;
  }

    return null;
 }
}