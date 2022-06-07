<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseFormat
{
    private function response(
        ?string $message = null,
        mixed $payload = null,
        bool $status = true,
        int $response_code = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            "success" => $status,
            "message" => json_decode($message) ?? $message,
            "payload" => is_object($payload)
                ? $payload->response()->getData(true)
                : ["data" => $payload],
        ];

        if ($payload == null) {
            unset($response["payload"]);
        }

        return response()->json($response, $response_code);
    }

    public function successResponse(mixed $payload, ?string $message = null, int $response_code = Response::HTTP_OK): JsonResponse
    {
        return $this->response(
            message: $message,
            payload: $payload,
            status: true,
            response_code: $response_code
        );
    }

    public function errorResponse(string $message, int $response_code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return $this->response(
            message: $message,
            status: false,
            response_code: $response_code
        );
    }

    public function successResponseWithMessage(string $message, int $response_code = Response::HTTP_OK): JsonResponse
    {
        return $this->response(
            message: $message,
            response_code: $response_code
        );
    }
}
