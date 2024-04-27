<?php

namespace App\Service;

use App\Repository\ServiceRegistryRepository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\util\Connection;
use GuzzleHttp\Client;


class ServiceDiscoveryService 
{

 public function __construct(private ServiceRegistryRepository $serviceRegistryRepository){}


 public function services(){
  return $this->serviceRegistryRepository->services();
 }

 public function checkA()
 {
    $services = $this->serviceRegistryRepository->getRegistryFromCache();



    foreach ($services as &$service) {
        // $response = Http::timeout(1000000)->get($service['url'] . 'health')->json();

        // Log::info("SERVICE RESPONSE::", $response);

        // $service['status'] = $response->successful() ? 'healthy' : 'unhealthy';
        Log::info($service['url'] . 'health');
        return $service['url'] . 'health';
    }

    $this->serviceRegistryRepository->update($services);

    return response()->json(['message' => 'Health checks performed']);
 }

 public function check()
{
    $services = $this->serviceRegistryRepository->getRegistryFromCache();

    foreach ($services as &$service) {
        try {
           
            // $data  = Http::timeout(1000000)->get($service['url'] . 'health')->json();
            $data  = Http::get("http://127.0.0.1:8000/api/product/health")->json();
            Log::info( $data );
            return $data;
        } catch (\Exception $e) {
            $service['status'] = 'unhealthy';
            Log::info(":::::::::::::::::::");
            Log::error("Error occurred while checking health of service {$service['url']}: {$e->getMessage()}");
        }
    }

    $this->serviceRegistryRepository->update($services);

    return response()->json(['message' => 'Health checks performed']);
}


}