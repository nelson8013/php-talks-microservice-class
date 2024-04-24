<?php


namespace App\Service;

use App\Exceptions\ProductNotFoundException;
use App\Exceptions\ProductOutOfStockException;
use App\Exceptions\EmptyCartException;
use App\Exceptions\ProductQuantityNotSufficientException;
use App\Interfaces\CartServiceInterface;
use App\Repository\CartRepository;
use App\Repository\CartItemRepository;
use App\Models\Cart;
use Illuminate\Http\Request;


class CartService  implements CartServiceInterface {

    public function __construct(private CartRepository $cartRepository, private CartItemRepository $cartItemRepository){}


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
       $productResponse =  \Http::get("http://127.0.0.1:8000/api/product/exists/{$productId}")->json();
 
       if( $productResponse['success']) {
 
          $results[$productId] = $quantity;
          
       }else{
          throw new ProductNotFoundException("Product with ID {$productId} not found");
       }
      }
      return $results;
   }

   public function checkIfRequestedQuantityIsLessThanOrEqualToAvailableQuantity(array $productIdsAndQuantities){
      
      foreach($productIdsAndQuantities as $productId => $quantity) {

         $checkAvailableProductResponse =  \Http::get("http://127.0.0.1:8001/api/inventory/quantity/{$productId}")->json();

         if( $checkAvailableProductResponse['data'] >= $quantity) {
  
            return true;

         }else{
            \Log::info("AVAILABLE QUANTITY", $checkAvailableProductResponse['data']);
            \Log::info("REQUESTED QUANTITY", $quantity);
            return false;
         }
      }
   }

    
   
    
   
   public function calculateTotalAmountOfEachProductInCart(array $requestedQuantitiesArray, array $productIdsAndQuantities){
    
      $totalAmount = [];

     foreach($productIdsAndQuantities as $productId => $requestedQuantity){

         $quantity = $requestedQuantitiesArray[$productId];

         if ($quantity >= $requestedQuantity) {

             $productPrice = \Http::get("127.0.0.1:8000/api/product/price/{$productId}")->json();
             

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

       \Http::get("127.0.0.1:8001/api/inventory/subtract-quantity/$productId/$requestedQuantity")->json();
             
    }


    
}


