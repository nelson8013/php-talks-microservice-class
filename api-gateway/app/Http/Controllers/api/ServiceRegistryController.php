<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRegistryRequest;
use Illuminate\Http\JsonResponse;
use App\Service\ServiceRegistryService;
use App\Service\HealthService;
use App\Service\ServiceDiscoveryService;
use App\Exceptions\ServiceNotFoundException;

class ServiceRegistryController extends Controller
{
    public function __construct(private ServiceRegistryService $serviceRegistryService, private ServiceDiscoveryService $serviceDiscoveryService, private HealthService $healthService){}


    public function health()
    {
        // TODO: @Nelson, Perform any necessary health checks
        // For example, check database connectivity, external dependencies, etc.

        $status = $this->healthService->health();
        return response()->json(['status' => $status, 'message' => 'Service status retrieved successfully'],201);
    }

    public function check() : JsonResponse
    {
        try {
            $serviceRegister = $this->serviceDiscoveryService->check();

            return response()->json(['data' => $serviceRegister, 'message' => 'Health checks performed'],200);
           
                
        } catch (ServiceNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }

    public function services() : JsonResponse
    {
        try {
            $serviceRegister = $this->serviceDiscoveryService->services();
            if($serviceRegister == !null){
                return response()->json(['data' => $serviceRegister, 'total available' => count($serviceRegister), 'message' => 'Services retrieved successfully'],200);
            }else{
                return response()->json(['data' => $serviceRegister, 'message' => 'No Services available yet.'],200);
            }
                
        } catch (ServiceNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }


    public function register(ServiceRegistryRequest $request) : JsonResponse
    {
        $serviceRegistry = $this->serviceRegistryService->register($request);
        return response()->json(['data' => $serviceRegistry,'message' => 'Service registered successfully'],201);
    }


    public function resolve(string $serviceName): JsonResponse
    {
        try {
            $serviceInfo = $this->serviceRegistryService->resolve($serviceName);
            return response()->json(['data' => $serviceInfo]);
        } catch (ServiceNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }

    public function flush(): JsonResponse
    {
        try {
            $this->serviceRegistryService->flush();
            return response()->json(['message' => "Service record flushed successfully!"]);
        } catch (ServiceNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage()], 404);
        }
    }

}
