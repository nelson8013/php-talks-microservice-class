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


class CartService implements CartServiceInterface {

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

       $results     = [];
       $results     = $this->checkIfProductsExistsAndGetQuantity($productIdsAndQuantities);
       $amounts     = $this->calculateTotalAmountOfEachProductInCart($results, $productIdsAndQuantities );
       $totalAmount = $this->calculateTotalAmountOfCart($amounts);
       $cartData    = ['totalAmount' => $totalAmount];

       $cart        = $this->cartRepository->save($cartData);

       $cartExists  = $this->cartRepository->existsById($cart->id);

       if($cartExists){
          return $this->addCartItems($amounts, $productIdsAndQuantities, $cart);
       }
    }

    public function calculateTotalAmountOfEachProductInCart(array $results, array $productIdAndQuantities){
     $totalAmount = [];

     foreach($results as $productId => $availableQuantity){
         $requestedQuantity = $productIdAndQuantities[$productId];
         if ($availableQuantity >= $requestedQuantity) {

             $productPrice = \Http::get(`127.0.0.1:8000/api/product/price/{$productId}`)->json();

             $totalAmount[$productId]  = $requestedQuantity * $productPrice;
         }else {
             \Log::info("Product quantity not sufficient for product ID: $productId");

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

    public function checkIfProductsExistsAndGetQuantity(array $productIdsAndQuantities){
     
     foreach($productIdsAndQuantities as $productId => $quantity) {
      $productResponse =  \Http::get("http://127.0.0.1:8000/api/product/exists/{$productId}")->json();
      return $productResponse;
      if( $productResponse['success']) {

         $prodQuantityCheckResponse =  \Http::get("http://127.0.0.1:8001/api/inventory/quantity/{$productId}")->json();
         $results[$productId] =  $prodQuantityCheckResponse;
      
      }else{

         throw new ProductNotFoundException("Product with ID {$productId} not found");
      
      }
     }

     return $results;
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
   
 }
    }

    
}


