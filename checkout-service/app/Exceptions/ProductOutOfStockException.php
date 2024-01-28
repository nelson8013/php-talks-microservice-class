<?php

namespace App\Exceptions;

use Throwable;
use Exception;

class ProductOutOfStockException extends Exception
{

     /**
     * Create a new ProductOutOfStockException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Product is out of stock", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}



















