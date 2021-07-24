<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\DivisionRequest;

class DivisionService
{
    public function divisionFloat(DivisionRequest $dto): float
    {
        return $dto->getDividend() / $dto->getDivider();
    }
}
