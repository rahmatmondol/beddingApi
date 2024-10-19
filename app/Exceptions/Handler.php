<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof HttpException) {
                return $this->handleHttpException($exception);
            }

            // Handle other exceptions specific to your application here if needed.
        }

        return parent::render($request, $exception);
    }

    protected function handleHttpException(HttpException $exception): JsonResponse
    {
        $statusCode = $exception->getStatusCode();
        $message = $this->getHttpStatusMessage($statusCode);

        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'error' => $exception->getMessage(),
        ], $statusCode);
    }

    protected function getHttpStatusMessage($statusCode): string
    {
        $statusMessages = [
            404 => 'Resource/URL not found',
            500 => 'Internal Server Error',
            405 => 'Method is not supported',
            403 => 'Forbidden',
            // Add more status codes and messages as needed.
        ];

        return $statusMessages[$statusCode] ?? 'Unknown Status Code';
    }
}
