<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class ProductQuantityNotSufficientException extends Exception
{

     /**
     * Create a new ProductQuantityNotSufficientException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Product quantity not sufficient", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}



















