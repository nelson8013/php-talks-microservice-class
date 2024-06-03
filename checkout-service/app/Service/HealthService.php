<?php


namespace App\Service;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Foundation\Application;


class HealthService {

 public function health()
    {
        $status = 'healthy';
        Log::info("CHECKOUT:-Java CAME HERE");
        // TODO: @Nelson, Perform any necessary health checks
        // For example, check database connectivity, external dependencies, etc.

        return  $status;
    }

}