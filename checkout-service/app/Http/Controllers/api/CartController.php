<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest;
use App\Service\CartService;
use App\Service\HealthService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    

    public function __construct(private CartService $cartService, private HealthService $healthService){}


    public function health()
    {
        // TODO: @Nelson, Perform any necessary health checks
        // For example, check database connectivity, external dependencies, etc.

        $status = $this->healthService->health();
        return response()->json(['status' => $status, 'message' => 'Service status retrieved successfully'],201);
    }


    /* @Request payload
                {           
                 "3": 3,
                 "2": 4,
                 "1": 2
                }
                {
                 "product_id": quantity,
                 "product_id": quantity,
                 "product_id": quantity,
                }

    */
    public function addToCart(Request $request) : JsonResponse {
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