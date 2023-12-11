<?php
namespace App\Enum;

use MyCLabs\Enum\Enum;

class SampleStatusEnum extends enum
{
    const STATUS_ONE = 'Status one';
    const STATUS_TWO = 'Status two';
    const STATUS_THREE = 'Status three';

    public static function isValid($value): bool
    {
        return in_array($value, array_map('strval', self::values()), true);
    }
}