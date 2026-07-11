<?php

namespace App\Domains\Docus\Enum;

enum DocuLang: string
{
    case FR = 'fr';
    case EN = 'en';
    case BIL = 'bil';
    case OTHER = 'other';

    function label(): string
    {
        return match ($this) {
            self::FR => "Français",
            self::EN => "Anglais",
            self::BIL => "Bilingue",
            self::OTHER => "Autre",
        };
    }
}
