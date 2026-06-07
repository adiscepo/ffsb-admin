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

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 dark:border-zinc-700 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-zinc-500">Support</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">Bug {{ $bug->id }}</span>
            </div>
        </nav>
    </header>
</x-slot>

<main class="">
</main>
