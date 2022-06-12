<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Request;

trait Filterable
{
    public function validateFiltering(Request $request): array
    {
        try {
            $data = $request->validate([
                "limit" => "sometimes|numeric|min:5",
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }
}