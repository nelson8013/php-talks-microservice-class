<?php

namespace App\Repository;
use Illuminate\Support\Facades\Cache;




class ServiceRegistryRepository
{

  private $cacheKey = 'service_registry';

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

  private function getRegistryFromCache(): array
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
}