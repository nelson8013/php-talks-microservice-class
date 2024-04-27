<?php

namespace App\Repository;
use Illuminate\Support\Facades\Cache;




class ServiceRegistryRepository
{

  private $cacheKey = 'service_registry';

  public function services(): array
  {
      $serviceRegistry = $this->getRegistryFromCache();
      $services = [];

      foreach ($serviceRegistry as $serviceName => $serviceData) {
          $services[$serviceName] = $serviceData;
      }

      return $services;
  }

  public function create(string $serviceName,$serviceUrl,$servicePort) : bool
  {
    $serviceRegistry = $this->getRegistryFromCache();

    $serviceRegistry[$serviceName] = [
      'name' => $serviceName,
      'url'  => $serviceUrl,
      'port' => $servicePort,
    ];

    Cache::put($this->cacheKey, $serviceRegistry);
    return true;
  }

  public function getRegistryFromCache(): array
  {
    return Cache::get($this->cacheKey, []);
  }

  public function getRegistryByName(string $serviceName)
  {
      $serviceRegistry = $this->getRegistryFromCache();
      return $serviceRegistry[$serviceName] ?? null;
  }

  public function flush(){
    return Cache::flush();
  }

  public function update(array $services){
    return Cache::put($this->cacheKey, $services);
  }
}