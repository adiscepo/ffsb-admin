<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;

new class extends Component {
    public ?Bug $bug;

    public function mount(int $id)
    {
        $this->bug = Bug::find($id);
        if ($this->bug == null) {
            return $this->redirect('/support/bugs');
        }
    }
};
?>

@include('partials.heading', ['route' => 'Support/Bugs/#' . $bug->id])

<main class="">
</main>
