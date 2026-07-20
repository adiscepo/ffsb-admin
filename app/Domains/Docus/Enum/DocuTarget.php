<?php

namespace App\Domains\Docus\Enum;

enum DocuTarget: string
{
    case PUBLIC = "public";
    case SCHOOL = "school";
    case EVENING = "evening";
    case FAMILY = "family";
    case YOUNG = "young";

    function label(): string
    {
        return match ($this) {
            self::PUBLIC => "Tout public",
            self::SCHOOL => "Ecoles",
            self::EVENING => "Soirées",
            self::FAMILY => "Familial",
            self::YOUNG => "Jeunes",
            // self::SP => "Espagnol",
            // self::IT => "Italien",
            // self::NL => "Néerlandais",
        };
    }

    static function toArray(): array
    {
        // dd(self::cases()[0]);
        $res = [];
        for ($i = 0; $i < sizeof(self::cases()); $i++) {
            array_push($res, [
                'id' => $i + 1,
                'name' => self::cases()[$i]->label()
            ]);
        }
        return $res;
    }

    static function id(string $label): int
    {
        foreach (DocuTarget::cases() as $id => $case) {
            if ($case->value == $label) return $id + 1;
        }
        return 0;
    }

    static function fromId(int $id): DocuTarget
    {
        return DocuTarget::cases()[$id - 1];
    }
}
