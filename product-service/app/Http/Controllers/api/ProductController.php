<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Service\ProductService;
use App\Service\HealthService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService, private HealthService $healthService){}


    public function health()
    {
        // TODO: @Nelson, Perform any necessary health checks
        // For example, check database connectivity, external dependencies, etc.

        $status = $this->healthService->health();
        return response()->json(['status' => $status, 'message' => 'Service status retrieved successfully'],201);
    }

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

    public function updateProduct(int $productId, ProductRequest $request) : JsonResponse
    {
        $product = $this->productService->updateProduct($productId,$request);
        return response()->json(['data' => $product, 'status' => true, 'message' => 'product updated successfully' ], 201);
    }

    public function doesProductExist(int $id) : bool|JsonResponse
    {
        $product = $this->productService->existsById($id);
        
        if($product == 1){
            return response()->json(['success' => true, 'message' => "Product exists",], 200);
         }
         else{
            return response()->json(['success' => false, 'message' => "Product does not exists",], 404);
         }
    }

    public function productPrice(int $id)
    {
        return  $this->productService->getProductPrice($id);
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productService->delete($id);

        if($product){
            return response()->json(['success' => true, 'message' => "Product deleted successfully!",], 200);
         }
         else{
            return response()->json(['success' => false, 'message' => "Product wasn't deleted",], 404);
         }
    }
}
