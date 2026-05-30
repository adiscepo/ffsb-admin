<?php

namespace App\Domains\Edition;

use App\Models\EditionYear;

class Edition
{
    public function currentEdition()
    {
        return EditionYear::where('current', true)->orderBy('year', 'desc')->first();
    }

    public function allEditions()
    {
        return EditionYear::orderBy('year', 'desc')->get();
    }
}
