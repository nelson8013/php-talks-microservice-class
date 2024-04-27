<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class ServiceNotFoundException extends Exception
{

     /**
     * Create a new ServiceNotFoundException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Service not found in the registry.", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}



















