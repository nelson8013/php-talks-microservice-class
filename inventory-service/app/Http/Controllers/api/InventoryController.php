<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Service\InventoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductQuantityRequest;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $inventoryService){}

    public function inventory(int $id) : JsonResponse
    {
        $inventory = $this->inventoryService->getInventory($id);
        return response()->json(['data' => $inventory, 'status' => true, 'message' => 'Inventory retrieved successfully' ], 200);
    }

    public function inventories() : JsonResponse
    {
        $inventories = $this->inventoryService->getInventories();
        return response()->json(['data' => $inventories, 'status' => true, 'message' => 'Inventories retrieved successfully' ], 200);
    }

    public function addInventory(InventoryRequest $request) 
    {
        $inventory = $this->inventoryService->create($request);

        if($inventory != false){
            return response()->json(['data' => $inventory, 'status' => true, 'message' => 'Inventory added successfully' ], 201);
        }
        return response()->json(['data' => $inventory, 'status' => false, 'message' => 'Inventory was not added. Inventory may already exist.' ], 400);
    }

    public function updateProductQuantity(ProductQuantityRequest $request) : JsonResponse
    {
        $this->inventoryService->updateProductQuantity($request);
        return response()->json(['status' => true, 'message' => 'Inventory updated quantity successfully' ], 201);
    }

    public function getProductQuantity(int $productId) 
    {
        return  $this->inventoryService->getProductQuantity($productId);
    }
}
