<?php

namespace App\Http\Controllers;

use Exception;
use App\Traits\ApiResponseFormat;
use App\Traits\HasExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function index(Request $request)
    {
        try {
            $data = $this->repository->index($request);

            $current_page = LengthAwarePaginator::resolveCurrentPage();
            $per_page = $request->limit ?? 10;

            $currentItems = array_slice($data, $per_page * ($current_page - 1), $per_page);
            $paginator = new LengthAwarePaginator($currentItems, count($data), $per_page, $current_page);
            $results = $paginator->withPath(config("app.url")."/api/clients");
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse($results, "Fetched successfully");
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

    public function show(string $id)
    {
        try {
            $data = $this->repository->show($id);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponse($data, "Fetched successfully");
    }
}
