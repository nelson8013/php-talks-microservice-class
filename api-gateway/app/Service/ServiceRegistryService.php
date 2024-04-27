<?php

namespace App\Service;

use App\Exceptions\ServiceNotFoundException;
use App\Repository\ServiceRegistryRepository;
use App\Http\Requests\ServiceRegistryRequest;
use App\Interfaces\ServiceRegistryInterface;


class ServiceRegistryService 
{

 public function __construct(private ServiceRegistryRepository $serviceRegistryRepository){}


 public function services(){
  return $this->serviceRegistryRepository->services();
 }
 public function register(ServiceRegistryRequest $request) : bool
 {
    return  $this->serviceRegistryRepository->create($request->name, $request->url, $request->port);
 }


  public function resolve(string $serviceName)
  {
        $serviceRegistry = $this->serviceRegistryRepository->getRegistryByName($serviceName);

        if (!$serviceRegistry) {
            throw new ServiceNotFoundException("Service '$serviceName' not found in the registry.");
        }

        return [
            'url'  => $serviceRegistry['url'],
            'port' => $serviceRegistry['port'],
        ];
  }

  public function flush(){
    return $this->serviceRegistryRepository->flush();
  }
}