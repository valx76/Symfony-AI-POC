<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class DatabaseDto
{
    /**
     * @param TableDto[] $tables
     */
    public function __construct(
        public ?int $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\Count(min: 1)]
        #[Assert\Valid]
        public array $tables,
    ) {
    }
}
