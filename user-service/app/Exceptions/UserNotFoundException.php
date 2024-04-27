<?php

namespace App\Exceptions;

use Throwable;
use Exception;
use Illuminate\Http\JsonResponse;

class UserNotFoundException extends Exception
{

     /**
     * Create a new UserNotFoundException instance.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "User not found", $code = 404, Exception $previous = null)
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
            'error'   => 'User Not Found',
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}



















