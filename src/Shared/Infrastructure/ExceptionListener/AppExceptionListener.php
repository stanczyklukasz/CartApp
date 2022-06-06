<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ExceptionListener;

use App\Shared\Infrastructure\Exception\AppException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class AppExceptionListener
{
    private static function prepareResponse(\Throwable $exception): JsonResponse
    {
        $response = new JsonResponse($exception->getMessage(), $exception->getCode());

        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof AppException) {
            if ($exception->getCode() === 0) {
                return;
            }

            $event->setResponse(self::prepareResponse($exception));
        }

        if ($exception->getPrevious() && $exception->getPrevious() instanceof AppException) {
            $exception = $exception->getPrevious();

            if ($exception->getCode() === 0) {
                return;
            }

            $event->setResponse(self::prepareResponse($exception));
        }
    }
}