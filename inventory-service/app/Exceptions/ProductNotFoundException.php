<?php

namespace App\Exceptions;

use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductNotFoundException extends Exception
{

     /**
     * Create a new ProductNotFoundException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Product not found", $code = 404, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

        /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            'error'   => 'Product Not Found',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}



















