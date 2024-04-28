<?php

namespace App\Service;

use App\Exceptions\ProductNotFoundException;
use App\Repository\ProductRepository;
use App\Http\Requests\ProductRequest;


class ProductService 
{

 public function __construct(private ProductRepository $productRepository){}


 public function existsById(int $id){
  try{
      return $this->productRepository->existsById($id);
  
  }catch (ProductNotFoundException $exception) {

    return response()->json([
        'error' => 'Product Does not Exist',
        'message' => $exception->getMessage(),
    ], 404);

  } catch (\Exception $exception) {
    
      return response()->json([
          'error' => 'Product Not Found',
          'message' => $exception->getMessage(),
      ], 500);
  }
 }

}