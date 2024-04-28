<?php


namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Foundation\Application;


class ServiceRegistrationService {


    /* public static function registerService()
    {
      Log::info("Attempting to register product service...");

      $serviceName = 'user-service'; 
      $servicePort =  8004;
      $serviceUrl  = "http://127.0.0.1:$servicePort/api/user/"; 

      $response = Http::post('http://127.0.0.1:8003/api/gate/register', [
          'name' => $serviceName,
          'url'  => $serviceUrl,
          'port' => $servicePort,
      ]);

      if ($response->successful()) {
          Log::info("User Service Registered Successfully");
          return true;
      } else {
          return false;
      }
    } */
}


