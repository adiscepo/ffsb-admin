<?php

namespace App\Domains\Programs\Enum;

enum ProgramEventKind: string
{
    case PROJECTION = "projection";
    case INTERVENTION = "intervention";
    case OTHER = "other";

    function label(): string
    {
        return match ($this) {
            self::PROJECTION => "Projection",
            self::INTERVENTION => "Intervention",
            self::OTHER => "Autre",
        };
    }
}
