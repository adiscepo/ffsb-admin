<?php

namespace App\Models\Enum;

enum DocuTarget: string
{
    case PUBLIC = "public";
    case SCHOOL = "school";
    case EVENING = "evening";

    function label() : string {
       return match($this) {
        self::PUBLIC => "Tout public",
        self::SCHOOL => "Ecoles",
        self::EVENING => "Soirées",
        // self::SP => "Espagnol",
        // self::IT => "Italien",
        // self::NL => "Néerlandais",
       };
    }
}
