<?php

namespace App\Tests\Integration\Dto;

use App\Dto\DatabaseDto;
use App\Dto\FieldDto;
use App\Dto\TableDto;
use App\Enum\FieldType;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FieldDtoTest extends KernelTestCase
{
    public function testValidationOkWhenNoTable(): void
    {
        $field = new FieldDto(id: null, name: 'FieldTest', type: FieldType::STRING->value, foreignKeyExtra: null);
        $table = new TableDto(id: null, name: 'TableTest', fields: [$field]);
        $database = new DatabaseDto(id: null, name: 'DatabaseTest', tables: [$table]);

        $violations = $this->getValidator()->validate($database);

        $this->assertCount(0, $violations);
    }

    public function testValidationOkWhenTableAndLinkedTableNameExists(): void
    {
        $field = new FieldDto(id: null, name: 'FieldTest', type: FieldType::TABLE->value, foreignKeyExtra: 'TableTest');
        $table = new TableDto(id: null, name: 'TableTest', fields: [$field]);
        $database = new DatabaseDto(id: null, name: 'DatabaseTest', tables: [$table]);

        $violations = $this->getValidator()->validate($database);

        $this->assertCount(0, $violations);
    }

    public function testValidationFailsWhenTableAndNoLinkedTableName(): void
    {
        $field = new FieldDto(id: null, name: 'FieldTest', type: FieldType::TABLE->value, foreignKeyExtra: null);
        $table = new TableDto(id: null, name: 'TableTest', fields: [$field]);
        $database = new DatabaseDto(id: null, name: 'DatabaseTest', tables: [$table]);

        $violations = $this->getValidator()->validate($database);

        $this->assertCount(1, $violations);
        $this->assertSame('The foreign key needs a linked table!', $violations[0]?->getMessage());
    }

    public function testValidationFailsWhenTableAndEmptyLinkedTableName(): void
    {
        $field = new FieldDto(id: null, name: 'FieldTest', type: FieldType::TABLE->value, foreignKeyExtra: '');
        $table = new TableDto(id: null, name: 'TableTest', fields: [$field]);
        $database = new DatabaseDto(id: null, name: 'DatabaseTest', tables: [$table]);

        $violations = $this->getValidator()->validate($database);

        $this->assertCount(1, $violations);
        $this->assertSame('The foreign key needs a linked table!', $violations[0]?->getMessage());
    }

    public function testValidationFailsWhenTableAndInvalidLinkedTableName(): void
    {
        $field = new FieldDto(id: null, name: 'FieldTest', type: FieldType::TABLE->value, foreignKeyExtra: 'NotExistingTable');
        $table = new TableDto(id: null, name: 'TableTest', fields: [$field]);
        $database = new DatabaseDto(id: null, name: 'DatabaseTest', tables: [$table]);

        $violations = $this->getValidator()->validate($database);

        $this->assertCount(1, $violations);
        $this->assertSame('The linked table does not exist!', $violations[0]?->getMessage());
    }

    private function getValidator(): ValidatorInterface
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var ValidatorInterface $validator */
        $validator = $container->get(ValidatorInterface::class);

        return $validator;
    }
}
