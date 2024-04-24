<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRegistryRequest;
use Illuminate\Http\JsonResponse;
use App\Service\ServiceRegistryService;
use App\Exceptions\ServiceNotFoundException;

class ServiceRegistryController extends Controller
{
    public function __construct(private ServiceRegistryService $serviceRegistryService){}


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
