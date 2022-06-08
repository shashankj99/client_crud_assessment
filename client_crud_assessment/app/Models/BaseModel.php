<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BaseModel
{
    protected string $filename = "";

    protected array $columns = [];

    private function checkFileExists(?string $file_name = null, bool $strict = false): bool
    {
        try {
            if (!empty($file_name) && is_readable($file_name)) {
                $contents = file($file_name);
                if(
                    !empty($contents)
                    && (
                        count($contents) > 1
                        || (
                            !empty($contents[0])
                            || ($strict && $contents[0] !== '')
                        )
                    )
                ) {
                    return true;
                }
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return false;
    }

    public function checkIfDataExists(string $data, ?string $id = null): string
    {
        try {
            $row = 0;
            $file = fopen(public_path("/storage/{$this->filename}"), "r");
            if (!$file) {
                throw new Exception("Unable to open the file");
            }
            while (($csv_data = fgetcsv($file, 1000, ",")) != false) {
                $row++;
                if ($row == 1) {
                    continue;
                }

                validate_email:
                if ($data == $csv_data[3]) {
                    if ($id && ($id == $csv_data[0])) {
                        goto validate_phone;
                    }
                    throw ValidationException::withMessages([
                        "email" => "The email address has already been taken",
                    ]);
                }

                validate_phone:
                if ($data == $csv_data[4]) {
                    if ($id && ($id == $csv_data[0])) {
                        continue;
                    }
                    throw ValidationException::withMessages([
                        "phone" => "The number has already been taken",
                    ]);
                }
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return $data;
    }

    public function create(array $data): void
    {
        try {
            $file = fopen(public_path($this->filename), "w");
            if (!$file) {
                throw new Exception("Unable to open the file");
            }
            if (!$this->checkFileExists($this->filename)) {
                fputcsv($file, $this->columns);
            }
            fputcsv($file, $data);
            fclose($file);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
