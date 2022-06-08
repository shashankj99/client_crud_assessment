<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\ApiResponseFormat;
use App\Traits\HasExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    use ApiResponseFormat;
    use HasExceptionHandler;

    public $repository;
    public string $model_name;
    public array $exception_statuses;
    public array $exception_messages;

    public function __construct()
    {
        $this->model_name = $model_name ?? str_replace("Controller", "", class_basename($this));
        $this->exception_statuses = array();
        $this->exception_messages = [
            ModelNotFoundException::class => "Unable to find the {$this->model_name}",
        ];
    }

    public function handleException(object $exception): JsonResponse
    {
        return $this->errorResponse(
            $this->getExceptionMessage($exception),
            $this->getExceptionStatus($exception)
        );
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->repository->validateData($request);
            $data["id"] = Str::uuid();
            $this->repository->store($data);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponseWithMessage(
            "Created successfully",
            Response::HTTP_CREATED
        );
    }
}
