<?php

namespace App\Interfaces;

use App\Http\Requests\ServiceRegistryRequest;

interface ServiceRegistryInterface
{
  public function register(ServiceRegistryRequest $request);
  public function resolve(string $serviceName);

}