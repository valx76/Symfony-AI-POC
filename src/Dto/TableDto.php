<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TableDto
{
    /**
     * @param FieldDto[] $fields
     */
    public function __construct(
        public ?int $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\Count(min: 1)]
        #[Assert\Valid]
        public array $fields,
    ) {
    }
}
