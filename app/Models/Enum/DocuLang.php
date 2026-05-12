<?php

namespace App\Models\Enum;

enum DocuLang: string
{
    case FR = 'fr';
    case EN = 'en';
    case BIL = 'bil';
    // case NL = 'nederlands';
    // case SP = 'spanish';
    // case IT = 'italian';

    function label() : string {
       return match($this) {
        self::FR => "Français",
        self::EN => "Anglais",
        self::BIL => "Bilingue",
        // self::SP => "Espagnol",
        // self::IT => "Italien",
        // self::NL => "Néerlandais",
       };
    }
}
