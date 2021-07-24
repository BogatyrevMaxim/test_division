<?php

declare(strict_types=1);

namespace App\DTO;


class DivisionResponse
{
    private float $result;

    public function __construct(float $result)
    {
        $this->result = $result;
    }

    public function getResult(): float
    {
        return $this->result;
    }
}
