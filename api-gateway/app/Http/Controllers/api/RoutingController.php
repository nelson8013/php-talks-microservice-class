<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoutingController extends Controller
{
    public function route(Request $request)
    {
        $endpoint = $request->path();
        
        $serviceUrl = $this->getServiceUrlForEndpoint($endpoint);


        if ($serviceUrl) {
            $response = Http::withHeaders($request->header())->send($request->method(), $serviceUrl, $request->all());
            
            return $response->body();
        } else {
            return response()->json(['error' => 'Service URL not found for endpoint'], 404);
        }
    }


    private function getServiceUrlForEndpoint($endpoint)
    {
        $serviceName = $this->parseServiceName($endpoint);
        $baseUrl = $this->getServiceBaseUrl($serviceName);

        if (!$baseUrl) {
            return null;
        }

        $additionalPath = substr($endpoint, strlen("/api/$serviceName"));

        return rtrim($baseUrl, '/') . '/' . ltrim($additionalPath, '/');
    }

    private function getServiceBaseUrl($serviceName)
    {
        switch ($serviceName) {
            case 'checkout':
                return 'http://127.0.0.1:8002/api/checkout';
            case 'inventory':
                return 'http://127.0.0.1:8001/api/inventory';
            case 'product':
                return 'http://127.0.0.1:8000/api/product';
            default:
                return null;
        }
    }


    private function parseServiceName($endpoint)
    {
        $segments = explode('/', $endpoint);
        return $segments[1] ?? null;
    }



        // private function getServiceUrlForEndpoint($endpoint)
    // {
    //     $serviceName = $this->parseServiceName($endpoint);
    
        
    //     switch ($serviceName) {
    //         case 'checkout':
    //             return 'http://127.0.0.1:8002/api/checkout';
    //         case 'inventory':
    //             return 'http://127.0.0.1:8001/api/inventory';
    //         case 'product':
    //             return 'http://127.0.0.1:8000/api/product';
    //         default:
    //             return null;
    //     }
    // }
    
}
