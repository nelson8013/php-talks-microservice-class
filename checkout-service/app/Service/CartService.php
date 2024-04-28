<?php


namespace App\Service;

use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductOutOfStockException;
use App\Exceptions\EmptyCartException;
use App\Exceptions\ProductQuantityNotSufficientException;
use App\Exceptions\InsufficientQuantityException;
use App\Interfaces\CartServiceInterface;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Repository\ProductRepository;
use App\Repository\InventoryRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use Illuminate\Http\Request;


class CartService  implements CartServiceInterface {

    public function __construct(private CartRepository $cartRepository, private CartItemRepository $cartItemRepository, private ProductRepository $productRepository, private InventoryRepository $inventoryRepository){}


    public function carts(){
      return $this->cartRepository->findAll();
    }

    public function cart(int $id){
      return $this->cartRepository->findById($id);
    }


    public function addToCart(Request $request) {
       
       $productIdsAndQuantities = $request->json()->all();

       if (empty($productIdsAndQuantities)) throw new EmptyCartException();

       $requestedQuantities   = [];
       $requestedQuantities   = $this->checkIfProductsExistsAndGetQuantityFromRequest($productIdsAndQuantities);
       $amounts     = $this->calculateTotalAmountOfEachProductInCart($requestedQuantities, $productIdsAndQuantities );
       $totalAmount = $this->calculateTotalAmountOfCart($amounts);
       $cartData    = ['totalAmount' => $totalAmount];

       $cart        = $this->cartRepository->save($cartData);

       $cartExists  = $this->cartRepository->existsById($cart->id);

       if($cartExists){
          return $this->addCartItems($amounts, $productIdsAndQuantities, $cart);
       }
    }

   public function checkIfProductsExistsAndGetQuantityFromRequest(array $productIdsAndQuantities){
     
      $results = [];
 
      foreach($productIdsAndQuantities as $productId => $quantity) {
       $productResponse =  $this->doesInventoryProductExist($productId); // Replaces the call to the product service
 
       if( $productResponse) {
         
          $results[$productId] = $quantity;
          
       }else{
          throw new ProductNotFoundException("Product with ID {$productId} not found");
       }
      }
      return $results;
   }

   public function checkIfRequestedQuantityIsLessThanOrEqualToAvailableQuantity(array $productIdsAndQuantities){
      
      foreach($productIdsAndQuantities as $productId => $quantity) {

         $checkAvailableProductResponse =  $this->getProductQuantity($productId); // Replaces the call to the Inventory service

         if( $checkAvailableProductResponse->quantity >= $quantity) {
  
            return true;

         }else{
            Log::info("AVAILABLE QUANTITY", $checkAvailableProductResponse['data']);
            Log::info("REQUESTED QUANTITY", $quantity);
            return false;
         }
      }
   }

    
   
    
   
    public function calculateTotalAmountOfEachProductInCart(array $requestedQuantitiesArray, array $productIdsAndQuantities){
    
      $totalAmount = [];

     foreach($productIdsAndQuantities as $productId => $requestedQuantity){

         $quantity = $requestedQuantitiesArray[$productId];

         if ($quantity >= $requestedQuantity) {

             $productPrice = Http::get("127.0.0.1:8000/api/product/price/{$productId}")->json();
             

             $totalAmount[$productId]  = $requestedQuantity * $productPrice;
         }else {

             throw new ProductQuantityNotSufficientException("The quantity available for product ID {$productId} is not enough to fulfil your order of $requestedQuantity.");
         }
     }

     return $totalAmount;
    }


    public function calculateTotalAmountOfCart(array $totalAmount){
      $sum = 0;
      foreach($totalAmount as $amount){
       $sum = $sum + $amount;
      }
      return $sum;
    }

    public  function addCartItems(array $amounts, array $productIdsAndQuantities, Cart $cart){
      foreach($amounts as $productId => $amt){
         $requestedQuantity = $productIdsAndQuantities[$productId];
      
         $cartItem = [
         'cart_id'    => $cart->id,
         'product_id' => $productId,
         'quantity'   => $requestedQuantity,
         'price'      => $amt
         ];

         
         $this->cartItemRepository->save($cartItem);
         $this->subtractRequestedQuantityFromInventory($productId, $requestedQuantity);
      }
    }

    public function subtractRequestedQuantityFromInventory(int $productId, int $requestedQuantity) : void
    {

       Http::get("127.0.0.1:8001/api/inventory/subtract-quantity/$productId/$requestedQuantity")->json();
             
    }

   /* Copy of method from the Product Service Team */
    public function doesInventoryProductExist(int $productId) : bool
    {
      return $this->productRepository->existsById($productId);
    }


   /* Copy of methodd from the Inventory Service Team */

    public function doesInventoryAlreadyExist(int $id) : bool
    {
     return $this->inventoryRepository->existsByProductId($id);
    }

    public function getProductQuantity(int $productId) : int|object
    {
      try{
          return $this->inventoryRepository->findProductQuantity($productId);
    
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


