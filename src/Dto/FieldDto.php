<?php

namespace App\Dto;

use App\Enum\FieldType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class FieldDto
{
    public function __construct(
        public ?int $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Choice(callback: [FieldType::class, 'values'])]
        public string $type,

        public ?string $foreignKeyExtra,
    ) {
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->type !== FieldType::TABLE->value) {
            return;
        }

        if (null === $this->foreignKeyExtra || 0 === strlen($this->foreignKeyExtra)) {
            $context->buildViolation('The foreign key needs a linked table!')
                ->atPath('foreignKeyExtra')
                ->addViolation();

            return;
        }

        /** @var DatabaseDto $database */
        $database = $context->getRoot();

        $tables = array_reduce($database->tables, function (array $carry, TableDto $table) {
            $carry[] = $table->name;

            return $carry;
        }, []);

        if (!in_array($this->foreignKeyExtra, $tables)) {
            $context->buildViolation('The linked table does not exist!')
                ->atPath('foreignKeyExtra')
                ->addViolation();
        }
    }
}
