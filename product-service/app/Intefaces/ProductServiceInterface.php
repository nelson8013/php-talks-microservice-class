<?php

namespace App\Interfaces;

use App\Http\Requests\ProductRequest;

interface ProductServiceInterface
{
  public function create(ProductRequest $request);

  public function getProducts();

  public function getProduct(int $id);

  public function getProductPrice(int $id);

  public function existsById(int $id);
}