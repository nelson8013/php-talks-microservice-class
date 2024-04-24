<?php

namespace App\Exceptions;

use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientQuantityException extends Exception
{

     /**
     * Create a new InsufficientQuantityException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "Insufficient quantity available for requested product", $code = 404, Exception $previous = null)
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
            'error'   => 'Insufficient quantity available for requested product',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}



















