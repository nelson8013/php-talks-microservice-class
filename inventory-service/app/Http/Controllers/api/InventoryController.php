<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Service\InventoryService;
use App\Service\HealthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductQuantityRequest;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $inventoryService, private HealthService $healthService){}


    public function health()
    {
        // TODO: @Nelson, Perform any necessary health checks
        // For example, check database connectivity, external dependencies, etc.

        $status = $this->healthService->health();
        return response()->json(['status' => $status, 'message' => 'Service status retrieved successfully'],201);
    }


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
        return response()->json(['data' => $inventory, 'status' => false, 'message' => 'Inventory was not added. Inventory may already exist, or the product Id may not exist.' ], 400);
    }

    public function updateProductQuantity(ProductQuantityRequest $request) : JsonResponse
    {
        $this->inventoryService->updateProductQuantity($request);
        return response()->json(['status' => true, 'message' => 'Inventory updated quantity successfully' ], 201);
    }

    public function getProductQuantity(int $productId) 
    {
        return $this->inventoryService->getProductQuantity($productId);
    }

    public function subtractProductQuantity(int $productId, int $quantityToSubtract) {
        $subtractOperation = $this->inventoryService->subtractQuantityAndUpdate($productId, $quantityToSubtract);

        if($subtractOperation != false){
            return response()->json(['data' => $subtractOperation, 'status' => true, 'message' => 'Quantity subtracted successfully' ], 201);
        }
        return response()->json(['data' => $subtractOperation, 'status' => false, 'message' => 'Quantity was not subtracted. The requested quantity may be more than available quantity, or the product Id may not exist.' ], 400);
 
    }
}
