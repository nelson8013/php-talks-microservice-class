<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Service\CartService;

class CartController extends Controller
{
    

    public function __construct(private CartService $cartService){}

    public function addToCart(AddToCartRequest $request) : JsonResponse {
        $this->cartService->addToCart($request);
        return response()->json(['message' => 'Cart added successfully'],201);
    }


    public function carts() : JsonResponse {
        $carts = $this->cartService->carts();
        return response()->json(['carts' => $carts,'message' => 'Carts retrieved successfully'],200);
    }

    public function cart(int $id) : JsonResponse {
        $cart = $this->cartService->cart($id);
        return response()->json(['carts' => $cart,'message' => 'Cart retrieved successfully'],200);
    }

}