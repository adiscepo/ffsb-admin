<?php

namespace App\Livewire\Forms;

use App\Domains\Docus\Docu;
use App\Domains\Docus\Enum\DocuLang;
use Illuminate\Support\Facades\Auth;
use Livewire\Form;

class DocuForm extends Form
{
    public string $title = "";
    public string $summary = "";
    public int $duration = 0;
    public int $year = 0;
    public DocuLang $lang = DocuLang::FR;
    public ?DocuLang $subtitles = null;
    public ?string $comments = null;

    protected function rules()
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'year' => 'required|integer',
            'duration' => 'required|integer',
            'lang' => 'required',
        ];
    }

    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $docu = Docu::create([
            'title' => $this->title,
            'summary' => $this->summary,
            'duration' => $this->duration,
            'year' => $this->year,
            'lang' => $this->lang,
            'subtitles' => $this->subtitles,
            'comment' => $this->comments,
            'found_by' => $user->id,
        ]);
        $this->reset();
    }
}
