<?php

namespace App\Models\Enum;

enum DocuLang: string
{
    case BIL = 'bilinqual';
    case FR = 'french';
    case EN = 'english';
    // case NL = 'nederlands';
    // case SP = 'spanish';
    // case IT = 'italian';

    function label() : string {
       return match($this) {
        self::BIL => "Bilingue",
        self::FR => "Français",
        self::EN => "Anglais",
        // self::SP => "Espagnol",
        // self::IT => "Italien",
        // self::NL => "Néerlandais",
       };
    }
}
