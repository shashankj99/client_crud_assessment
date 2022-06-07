<?php

namespace App\Models;

use Exception;

class BaseModel
{
    private function get_file($file_name = null, $strict = false): bool
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

    public function create(array $data): void
    {
        try {
            $file = fopen($this->filename, 'w');

            if (!$file) {
                throw new Exception("Unable to open the file");
            }

            if (!$this->get_file($this->filename)) {
                fputcsv($file, $this->columns);
            }

            fputcsv($file, $data);
            fclose($file);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
