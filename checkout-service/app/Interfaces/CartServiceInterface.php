<?php


namespace App\Interfaces;

use App\Models\Cart;
use Illuminate\Http\Request;


interface CartServiceInterface {

 public function carts();
 public function cart(int $id);
 public function addToCart(Request $request);

 public function calculateTotalAmountOfEachProductInCart(array $results, array $productIdAndQuantities);

 public function calculateTotalAmountOfCart(array $totalAmount);

 public function checkIfProductsExistsAndGetQuantity(array $productIdsAndQuantities);
 public  function addCartItems(array $amounts, array $productIdsAndQuantities, Cart $cart);

}