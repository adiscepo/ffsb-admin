<?php

namespace App\Helpers\HumanTiming;

function to_human(int $time, bool $small_format = true, bool $no_seconds = true) {
    $time = $time * 60;
    // $time = $time < 1 ? 1 : $time;
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
    $i = 0;
    $units = array(); 
    foreach ($tokens as $unit => $text) {
        if ($time < $unit or $i == 2 or ($unit == 1 and $no_seconds)) {
            continue;
        }
        $numberOfUnits = floor($time / $unit);
        array_push(
            $units, 
            $numberOfUnits . "" . $text . (($text != "mois" and !$small_format) ? ($numberOfUnits > 1 ? 's' : '') : "")
        );
        $time = $time - $unit;
        $i += 1;
    }
    return join("", $units);
}
