<?php

namespace App\Exceptions;

use App\Traits\ApiResponseFormat;
use App\Traits\HasExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseFormat;
    use HasExceptionHandler;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            $this->exception_messages = [];
            $this->exception_statuses = [
                HttpException::class => method_exists($exception, "getStatusCode")
                    ? $exception->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR,
            ];

            return $this->errorResponse(
                $this->getExceptionMessage($exception),
                $this->getExceptionStatus($exception)
            );
        } else {
            return parent::render($request, $exception);
        }
    }
}
