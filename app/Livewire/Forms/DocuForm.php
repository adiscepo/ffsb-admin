<?php

namespace App\Livewire\Forms;

use App\Models\Enum\DocuLang;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DocuForm extends Form
{
    public string $title = "";
    public string $summary = "";
    public int $duration = 0;
    public DocuLang $lang = DocuLang::FR;
    public ?DocuLang $subtitles = null; 
}
