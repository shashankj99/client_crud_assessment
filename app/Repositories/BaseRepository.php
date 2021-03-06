<?php

namespace App\Repositories;

use App\Traits\Filterable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BaseRepository
{
    use Filterable;

    public array $rules;
    public array $messages = [];
    public object $model;
    public ?string $model_name;

    public function validateData(
        object $request,
        array $rules = [],
        array $messages = [],
        ?callable $callback = null
    ): array {
        try {
            $data = $request->validate(
                array_merge($this->rules, $rules),
                array_merge($this->messages, $messages)
            );
            $append_data = $callback ? $callback($request) : [];
        } catch (Exception $exception) {
            throw $exception;
        }

        return array_merge($data, $append_data);
    }

    public function store(
        array $data,
        ?callable $after_create = null,
        ?callable $before_create = null
    ): void {
        try {
            if ($before_create) {
                $before_create();
            }

            $created = $this->model->create($data);
            if ($after_create) {
                $after_create($created);
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function index(Request $request, ?callable $callback = null): array
    {
        try {
            $filter = $this->validateFiltering($request);
            $data = $this->model->index($filter);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }

    public function show(string $id)
    {
        try {
            $data = $this->model->show($id);
            if (!$data) {
                throw new ModelNotFoundException("{$this->model_name} not found");
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }
}