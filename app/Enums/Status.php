<?php

namespace App\Enums;

enum Status :string
{
    case ReadyToSend =  'ready_to_send';
    case Send = 'send';
    case AtOrigin = 'at_origin';
    case AtDestination = 'at_destination';
    case EndService = 'end_service';

    public static function next(string $status): Status
    {
        $index = collect(static::cases())->search(fn($case) => $case->value === $status);
        return static::cases()[++$index] ?? static::last();
    }

    public static function last(): Status
    {
        $lastIndex = count(static::cases()) - 1;
        return static::cases()[$lastIndex];
    }
}
