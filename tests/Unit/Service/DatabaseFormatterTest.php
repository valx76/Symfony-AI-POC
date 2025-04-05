<?php

namespace App\Tests\Unit\Service;

use App\Entity\Database;
use App\Entity\Field;
use App\Entity\Table;
use App\Enum\FieldType;
use App\Service\DatabaseFormatter;
use PHPUnit\Framework\TestCase;

class DatabaseFormatterTest extends TestCase
{
    public function testFormatTheDatabase(): void
    {
        $database = $this->createDatabase();

        $databaseFormatter = new DatabaseFormatter();
        $formattedDatabase = $databaseFormatter->format($database);

        $expectedDatabase = <<<TXT
- table1 (field1_1 (string), field1_2 (integer))
- table2 (field2_1 (boolean), field2_2 (float), field2_3 (foreign key to "table1"))
TXT;

        $this->assertSame($expectedDatabase, $formattedDatabase);
    }

    private function createDatabase(): Database
    {
        $database = new Database();
        $database->setName('DB');

        $table1 = new Table();
        $table1->setName('table1');
        $table1->setDatabase($database);

        $field11 = new Field();
        $field11->setName('field1_1');
        $field11->setType(FieldType::STRING);
        $field11->setOwner($table1);
        $table1->addField($field11);

        $field12 = new Field();
        $field12->setName('field1_2');
        $field12->setType(FieldType::INT);
        $field12->setOwner($table1);
        $table1->addField($field12);

        $table2 = new Table();
        $table2->setName('table2');
        $table2->setDatabase($database);

        $field21 = new Field();
        $field21->setName('field2_1');
        $field21->setType(FieldType::BOOL);
        $field21->setOwner($table2);
        $table2->addField($field21);

        $field22 = new Field();
        $field22->setName('field2_2');
        $field22->setType(FieldType::FLOAT);
        $field22->setOwner($table2);
        $table2->addField($field22);

        $field23 = new Field();
        $field23->setName('field2_3');
        $field23->setType(FieldType::TABLE);
        $field23->setForeignKeyExtra('table1');
        $field23->setOwner($table2);
        $table2->addField($field23);

        $database->addTable($table1);
        $database->addTable($table2);

        return $database;
    }
}
