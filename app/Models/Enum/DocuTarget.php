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

    static function toArray(): array {
        // dd(self::cases()[0]);
        $res = [];
        for ($i=0; $i < sizeof(self::cases()); $i++) { 
            array_push($res, [
                'id' => $i + 1,
                'name' => self::cases()[$i]->label()
            ]);
        }
        return $res;
    }
}
