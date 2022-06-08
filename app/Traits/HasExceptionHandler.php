<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait HasExceptionHandler
{
    public array $exception_statuses;
    public array $exception_messages;

    public function getExceptionStatus(object $exception): int
    {
        $default_response_code = $exception->getCode() != 0
            ? $exception->getCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;
        return array_merge([
            ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
            ModelNotFoundException::class => Response::HTTP_NOT_FOUND,
            MethodException::class => Response::HTTP_INTERNAL_SERVER_ERROR,
        ], $this->exception_statuses)[get_class($exception)] ?? $default_response_code;
    }

    public function getExceptionMessage(object $exception): string
    {
        return array_merge([
            ValidationException::class => json_encode(
                method_exists($exception, "errors")
                    ? $exception->errors()
                    : []
            ),
        ], $this->exception_messages)[get_class($exception)] ?? $exception->getMessage();
    }
}
