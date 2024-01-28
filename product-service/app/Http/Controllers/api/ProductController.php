<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Service\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService){}

    public function product(int $id) : JsonResponse
    {
        $product = $this->productService->getProduct($id);
        
        return response()->json(['data' => $product, 'status' => true, 'message' => 'product retrieved successfully' ], 200);
    }

    public function products() : JsonResponse
    {
        $products = $this->productService->getProducts();

        return response()->json(['data' => $products, 'status' => true, 'message' => 'products retrieved successfully' ], 200);
    }

    public function addProduct(ProductRequest $request) : JsonResponse
    {
        $product = $this->productService->create($request);
        return response()->json(['data' => $product, 'status' => true, 'message' => 'product added successfully' ], 201);
    }

    public function doesProductExist(int $id) : bool
    {
        return $this->productService->existsById($id);
    }

    public function productPrice(int $id)
    {
        return  $this->productService->getProductPrice($id);
    }
}
