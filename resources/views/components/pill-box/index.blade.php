<?php
use Livewire\Component;
use App\Models\ProductionHouse;

new class extends Component {
    public string $query = '';
    public bool $selected = false;

    public array $datas = [];
    public array $results = [];

    public int $highlight_id = 0;

    public function mount()
    {
        $this->datas = ProductionHouse::all()->toArray();
    }
};

?>

@php
    $classes =
        'w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5 data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500 outline-none';
    $class_element = 'flex items-center gap-2 list-none p-2 w-full cursor-pointer [:where(&)]:hover:bg-zinc-200';
@endphp

<div x-data="{
    query: '',
    open: true,
    highlighted_id: 1,
    selected: [],
    showed: [],
    items: @js($this->datas),
    getShowed() {
        if (this.query == '') {
            return this.items.map(function(obj) {
                console.log(obj.id + ' ' + obj.name)
                return obj.id;
            });
        }
        return this.showed;
    },
    removeSelected(id) {
        var index = this.selected.indexOf(id);
        if (index !== -1) {
            this.selected.splice(index, 1);
        }
    },
    addSelected(id) {
        var index = this.selected.indexOf(id);
        if (index == -1) {
            this.selected.push(id)
        }
    },
    toggleSelected(id) {
        if (this.selected.indexOf(id) == -1) {
            this.addSelected(id)
        } else {
            this.removeSelected(id)
        }
    },
    decrementHighlight() {
        if (this.highlighted_id == this.items[0].id) {
            this.highlighted_id = this.items.length
            this.dropdownScroll()
            return
        }
        this.highlighted_id -= 1
        this.dropdownScroll()
    },
    incrementHighlight() {
        dropdown = document.getElementById('dropdown-element')
        if (this.items.length == 0) {
            this.highlighted_id = 1;
            this.dropdownScroll()
            return;
        }
        if (this.highlighted_id == this.items.length) {
            this.highlighted_id = 1
            this.dropdownScroll()
            return
        }
        this.highlighted_id += 1
        this.dropdownScroll()
    },
    updateElements() {
        console.log(this.query)
        this.showed = []
        for (id in this.items) {

            if (this.items[id].name.toLowerCase().includes(this.query.toLowerCase())) {
                this.showed.push(id)
            }
        }
    },
    dropdownScroll() {
        dropdown_current = document.getElementById('dropdown-item-' + this.highlighted_id)
        var scroll_offset = (this.highlighted_id - 1) * (dropdown_current.offsetHeight + 10);
        console.log(scroll_offset)
        {{-- dropdown.scroll({
            top: scroll_offset,
            behavior: 'smooth',
        }); --}}
        dropdown_current.scrollIntoView({ behavior: 'smooth', block: 'center',})
        
    }
}" class="relative" @click="open = true" @click.outside="open = false">
    <div class="flex flex-wrap gap-x-1">
        <template x-for="id_selected in selected" :key="id_selected">
            <flux:badge size="small" class="text-xs mb-2">
                <span x-text="items[id_selected - 1].name"></span>
                <flux:badge.close @click="removeSelected(id_selected)" />
            </flux:badge>
        </template>
    </div>
    <input class="{{ $classes }}" x-model='query' x-bind:class="open ? 'rounded-b-none border-b-0' : ''"
        x-on:input="open = true; updateElements()" x-on:keyup.up="decrementHighlight()"
        x-on:keyup.down="incrementHighlight()" x-on:keyup.enter="toggleSelected(highlighted_id)" />
    <div class="w-full flex flex-col text-sm items-stretch rounded-lg shadow-lg bg-white max-h-50 overflow-y-scroll rounded-t-none border-t-0 absolute border"
        x-show="open" x-id="['element']" id="dropdown-element">
        <template x-for="item in getShowed()" :key="item">
            <li x-bind:id="'dropdown-item-' + item" class="{{ $class_element }}" x-bind:class="item == highlighted_id ? 'bg-zinc-200' : ''"
                @click="toggleSelected(item)">
                <flux:icon.check x-show="selected.includes(item)" variant="micro" />
                <span x-text="items[item - 1].name"></span>
            </li>
        </template>
        <li class="{{ $class_element }} hover:bg-white" x-show="getShowed() == 0">Pas de résultats</li>
    </div>
</div>
