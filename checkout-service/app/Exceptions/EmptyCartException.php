<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class EmptyCartException extends Exception
{

     /**
     * Create a new EmptyCartException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Cart is empty. Please add items to proceed", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}



















