<?php

namespace App\Service;

use App\Dto\DatabaseDto;
use App\Entity\Database;
use App\Entity\Field;
use App\Entity\Table;
use App\Enum\FieldType;

class DatabaseTransformer
{
    public function createDatabaseEntityFromDto(DatabaseDto $dto): Database
    {
        $database = new Database();
        $database->setName($dto->name);

        foreach ($dto->tables as $tableDto) {
            $table = new Table();
            $table->setName($tableDto->name);
            $table->setDatabase($database);

            foreach ($tableDto->fields as $fieldDto) {
                $field = new Field();
                $field->setName($fieldDto->name);
                $field->setType(FieldType::from($fieldDto->type));
                $field->setForeignKeyExtra($fieldDto->foreignKeyExtra);
                $field->setOwner($table);

                $table->addField($field);
            }

            $database->addTable($table);
        }

        return $database;
    }
}
