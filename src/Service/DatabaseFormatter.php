<?php

namespace App\Service;

use App\Entity\Database;
use App\Entity\Field;
use App\Enum\FieldType;

class DatabaseFormatter
{
    public function format(Database $database): string
    {
        $formattedDatabase = '';

        foreach ($database->getTables() as $table) {
            $fields = array_reduce(
                $table->getFields()->toArray(),
                function (array $result, Field $field) {
                    $result[] =
                        (FieldType::TABLE === $field->getType()) ?
                            sprintf('%s (foreign key to "%s")', $field->getName(), $field->getForeignKeyExtra()) :
                            sprintf('%s (%s)', $field->getName(), $field->getType()->value);

                    return $result;
                },
                []
            );

            $formattedDatabase .= sprintf(
                "\n- %s (%s)",
                $table->getName(),
                implode(', ', $fields)
            );
        }

        // remove the first line break
        return substr($formattedDatabase, 1);
    }
}
