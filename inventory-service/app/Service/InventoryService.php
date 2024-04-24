<?php

namespace App\Service;

use App\Repository\InventoryRepository;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\ProductQuantityRequest;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\InsufficientQuantityException;


class InventoryService
{

 public function __construct(private InventoryRepository $inventoryRepository){}


 public function doesInventoryAlreadyExist(int $id) : bool
 {
  return $this->inventoryRepository->existsByProductId($id);
 }
 public function create(InventoryRequest $request){
    $response  = \Http::get("http://127.0.0.1:8000/api/product/exists/{$request->product_id}")->json();

    if( $response["success"]) {
      if(!$this->doesInventoryAlreadyExist($request->product_id)){
       $data = ['product_id' => $request->product_id, 'quantity' => $request->quantity,'is_available' => $request->is_available];
       return $this->inventoryRepository->save($data);
      }
      else{
       return true;
      }

    }else{
     return false;
    }
    
 }

 public function getProductQuantity(int $productId){
  try{
      $quantity = $this->inventoryRepository->findProductQuantity($productId);

      return response()->json(['success' => true, 'data' => $quantity,'message' => "Product Quantity retrieved successfully",], 200);

  }catch (ProductNotFoundException $exception) {

    return response()->json([
        'error' => 'Product Not Found',
        'message' => $exception->getMessage(),
    ], 404);

  } catch (\Exception $exception) {
    
      return response()->json([
          'error' => 'Product Not Found',
          'message' => $exception->getMessage(),
      ], 500);
  }
 }

 public function getInventories(){
  return $this->inventoryRepository->findAll();
 }

 public function getInventory(int $id){
  return $this->inventoryRepository->findById($id);
 }

 public function updateProductQuantity(ProductQuantityRequest $request){
  return $this->inventoryRepository->updateProductInventory($request->productId, $request->quantity);
 }

 /**
     * Subtract a quantity from a row in the inventory table before updating it.
     *
     * @param int $productId
     * @param int $quantityToSubtract
     * @return bool Whether the operation was successful or not
     */
  public function subtractQuantityAndUpdate(int $productId, int $quantityToSubtract): bool
  {
      try {
          $currentQuantity = $this->inventoryRepository->findProductQuantity($productId);

          if ($currentQuantity >= $quantityToSubtract) {
              $newQuantity = $currentQuantity - $quantityToSubtract;

              $this->inventoryRepository->updateProductInventory($productId, $newQuantity);

              return true;
          } else {
              throw new InsufficientQuantityException("Insufficient quantity available for product ID: {$productId}");
          }
      } catch (ProductNotFoundException $exception) {
        throw $exception;
      } catch (\Exception $exception) {
        throw $exception;
      }
  }

}