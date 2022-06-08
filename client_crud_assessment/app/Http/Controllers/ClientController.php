<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    public function __construct()
    {
        $this->repository = new ClientRepository();
        parent::__construct();
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->repository->validateClientData($request);
            $this->repository->store($data);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }

        return $this->successResponseWithMessage("Created successfully");
    }
}
