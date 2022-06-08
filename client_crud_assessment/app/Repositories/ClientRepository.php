<?php

namespace App\Repositories;

use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Client();
        $this->model_name = "Client";

        $this->rules = [
            "name" => ["required"],
            "gender" => ["required", Rule::in($this->model::$gender)],
            "email" => ["required", "email"],
            "phone" => ["required", 'regex:"(?:\+977[- ])?\d{2}-?\d{7,8}"'],
            "address" => ["required"],
            "nationality" => ["required", Rule::in($this->model::$nationality)],
            "dob" => ["required", "date"],
            "educational_background" => ["required", Rule::in($this->model::$educational_backgrounds)],
            "preferred_mode_of_contatc" => ["sometimes", "nullable", Rule::in($this->model::$preffered_mode_of_contact)],
        ];
    }

    public function validateClientData(Request $request): array
    {
        try {
            $data = $this->validateData($request, [], [], function ($data) use ($request) {
                $data["email"] = $this->model->checkIfDataExists($request->email);
                $data["phone"] = $this->model->checkIfDataExists($request->phone);
            });
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }
}