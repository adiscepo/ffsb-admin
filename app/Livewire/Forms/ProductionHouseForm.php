<?php

namespace App\Livewire\Forms;

use App\Models\ProductionHouse;
use Livewire\Form;

class ProductionHouseForm extends Form
{

    public function rules() {
        return [
            'name' => 'required',
        ];
    }

    public function store() {
        $this->validate();
        ProductionHouse::create(
            $this->all()
        );
    }
}
