<?php

declare(strict_types=1);

namespace App\DTO;

class ErrorsResponse
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
