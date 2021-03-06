<?php

namespace App\Models;

use Exception;
use Illuminate\Validation\ValidationException;
use SebastianBergmann\CodeCoverage\Report\PHP;
use SplFileObject;

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
            $file = fopen(public_path($this->filename), "r");
            if (!$file) {
                throw new Exception("Unable to open the file");
            }
            while (($csv_data = fgetcsv($file, 1000, ",")) != false) {
                $row++;
                if ($row == 1) {
                    continue;
                }

                validate_email:
                if ($data == $csv_data[2]) {
                    if ($id && ($id == $csv_data[9])) {
                        goto validate_phone;
                    }
                    throw ValidationException::withMessages([
                        "email" => "The email address has already been taken",
                    ]);
                }

                validate_phone:
                if ($data == $csv_data[3]) {
                    if ($id && ($id == $csv_data[9])) {
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
            $file = fopen(public_path($this->filename), "a");
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

    public function index(array $data): array
    {
        try {
            $row = 0;
            $file = fopen(public_path($this->filename), "r");
            if (!$file) {
                throw new Exception("Unable to open the file");
            }
            while (($csv_data = fgetcsv($file, 1000, ",")) != false) {
                $row++;
                if ($row == 1) {
                    continue;
                }
                $generated_data[] = $csv_data;
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return $generated_data;
    }

    public function show(string $id)
    {
        try {
            $file = fopen(public_path($this->filename), "r");
            if (!$file) {
                throw new Exception("Unable to open the file");
            }
            while (($csv_data = fgetcsv($file, 1000, ",")) != false) {
                if (isset($csv_data[9]) && $csv_data[9] == $id) {
                    return $csv_data;
                }
            }
        } catch (Exception $exception) {
            throw $exception;
        }

        return null;
    }
}
