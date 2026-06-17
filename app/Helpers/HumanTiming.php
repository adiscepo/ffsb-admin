<?php

namespace App\Helpers\HumanTiming;

function to_human(int $time_in_minutes, bool $small_format = true, bool $no_seconds = true): string
{
    // Convert minutes to seconds
    $time = $time_in_minutes * 60;

    $tokens = [
        31536000 => 'an',
        2592000 => 'mois',
        604800 => 'semaine',
        86400 => 'jour',
        3600 => 'heure',
        60 => 'minute',
        1 => 'seconde',
    ];

    if ($small_format) {
        $tokens = [
            31536000 => 'an',
            2592000 => 'mois',
            604800 => 'sem',
            86400 => 'j',
            3600 => 'h',
            60 => 'min',
            1 => 'sec',
        ];
    }

    $units = [];
    foreach ($tokens as $unit => $text) {
        if ($time == 0) break;

        // Skip if time is less than unit or if we don't want seconds
        if ($time < $unit || ($unit == 1 && $no_seconds)) {
            continue;
        }

        $numberOfUnits = intdiv($time, $unit);

        $unitString = $numberOfUnits;
        if (!$small_format && $text !== 'mois') {
            $unitString .= $text . ($numberOfUnits > 1 ? 's' : '');
        } else {
            $unitString .= $text;
        }

        $units[] = $unitString;
        $time %= $unit;
    }

    return implode('', $units);
}
