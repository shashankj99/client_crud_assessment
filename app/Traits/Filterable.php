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
                "limit" => "required|numeric|min:5",
                "offset" => "required|numeric|min:0",
            ]);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }
}