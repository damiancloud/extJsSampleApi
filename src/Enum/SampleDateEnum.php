<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;

class SampleDateEnum extends enum
{
    const CREATED = 'created';
    const LAST = 'last';

    public static function isValid($value): bool
    {
        return in_array($value, array_map('strval', self::values()), true);
    }
}