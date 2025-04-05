<?php

namespace App\Enum;

enum FieldType: string
{
    case STRING = 'string';
    case INT = 'integer';
    case FLOAT = 'float';
    case BOOL = 'boolean';
    case TABLE = 'table';

    /** @return string[] */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
