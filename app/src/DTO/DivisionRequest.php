<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DivisionRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\LessThanOrEqual(PHP_FLOAT_MAX, message="This value should be less than or equal to PHP_FLOAT_MAX")
     * @Assert\Expression(
     *     "value >= -constant('PHP_FLOAT_MAX')",
     *     message="This value should be great than or equal to -PHP_FLOAT_MAX."
     * )
     * @Assert\Type("float")
     */
    private ?float $dividend;
    /**
     * @Assert\NotBlank
     * @Assert\NotEqualTo(0)
     * @Assert\Expression(
     *     "value > constant('PHP_FLOAT_MIN') or value < -constant('PHP_FLOAT_MIN')",
     *     message="This value should be greater than PHP_FLOAT_MIN or less than -PHP_FLOAT_MIN"
     * )
     * @Assert\LessThanOrEqual(PHP_FLOAT_MAX, message="This value should be less than or equal to PHP_FLOAT_MAX")
     * @Assert\Expression(
     *     "value >= -constant('PHP_FLOAT_MAX')",
     *     message="This value should be less than or equal to -PHP_FLOAT_MAX."
     * )
     * @Assert\Type("float")
     */
    private ?float $divider;

    public function getDividend(): ?float
    {
        return $this->dividend;
    }

    public function setDividend(float $dividend): self
    {
        $this->dividend = $dividend;

        return $this;
    }

    public function getDivider(): ?float
    {
        return $this->divider;
    }

    public function setDivider(float $divider): self
    {
        $this->divider = $divider;

        return $this;
    }
}
