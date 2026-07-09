<?php
use Livewire\Component;
use App\Domains\ProductionHouses\ProductionHouse;

new class extends Component {
    public string $name = '';
    public string $event_name = '';
    public string $query = '';
    public array $selected = [];

    public array $datas = [];
    public array $results = [];
    protected bool $one_result = false;

    public int $highlight_id = 0;
    public string $key = 'name';

    protected function getListeners()
    {
        if ($this->event_name != '') {
            return [
                $this->event_name => 'updateDatas',
            ];
        }
        return [];
    }

    /** array $data: liste des éléménts à afficher dans le menu dropdown
     *               La liste doit être composée d'éléments de la forme
     *                 [
     *                    'id' => x,
     *                     $data_key => 'value',
     *                 ]
     *  string $key: la clé dans le dicitionnaire des données à afficher dans
     *               la liste déroulante
     *  string $name: (optionnel) permet d'activer un listener de mise à jour
     *                 de données (update-datas-'$name')
     *  ATTENTION: Datas contient des éléments dont le premier est à l'indice 1
     *             c'est dû au fait que les éléménts dans la base de données
     *             commencent à l'id 1
     */
    public function mount(array $datas, string $data_key = 'name', string $name = '', string $event_name = '', bool $one_result = false, array $selected = [])
    {
        $this->name = $name;
        $this->event_name = $event_name;
        $this->datas = $datas;
        $this->key = $data_key;
        $this->one_result = $one_result;
        $this->selected = $selected;
        // Workaround to prevent list of null element to be considered as filled
        if ($this->selected == [null]) {
            $this->selected = [];
        }
    }

    public function updateDatas(array $datas)
    {
        $this->datas = $datas;
    }

    public function oneResult()
    {
        return $this->one_result;
    }
};
?>

@php
    $classes =
        'w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5 data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500 outline-none';
    $class_element =
        'flex items-center gap-2 list-none p-2 w-full cursor-pointer [:where(&)]:hover:bg-zinc-200 dark:hover:bg-white dark:hover:bg-zinc-600';
@endphp

<div x-data="{
    query: '',
    open: false,
    name: @js($this->name),
    highlighted_id: null, // Prevent to have an highlight of the first element
    one_result: @js($this->one_result),
    selected: @js($this->selected),
    showed: [],
    key: @js($this->key),
    items: $wire.entangle('datas'),
    getShowed() {
        if (this.query == '') this.updateElements()
        return this.showed.sort(function(a, b) {
            return a - b;
        });;
    },
    getItem(id) {
        return this.items.find(i => i.id === id);
    },
    severalElements() {
        return !this.one_result;
    },
    removeSelected(id) {
        var index = this.selected.indexOf(id);
        if (index !== -1) {
            this.selected.splice(index, 1);
        }
        $wire.dispatch('pill-box:' + this.name, { selected: this.selected })
    },
    addSelected(id) {
        if (this.severalElements() || (!this.severalElements() && this.selected.length == 0)) {
            var index = this.selected.indexOf(id);
            if (index === -1) {
                this.selected.push(id)
            }
        }
        $wire.dispatch('pill-box:' + this.name, { selected: this.selected })
    },
    toggleSelected(id) {
        if (id != null) {
            if (this.selected.includes(id)) {
                this.removeSelected(id)
            } else {
                this.addSelected(id)
            }
            $wire.dispatch('pill-box:' + this.name, { selected: this.selected })
        }
    },
    getHigh() {
        return this.highlighted_id
    },
    onlyOneElement() {
        return this.getShowed().length == 1
    },
    incrementHighlight() {
        var showed = this.getShowed()
        if (this.onlyOneElement()) { this.highlighted_id = showed[0]; return }
        var max_showed = showed[showed.length - 1]
        if (this.getHigh() + 1 > max_showed) {
            this.highlighted_id = showed[0]
            this.dropdownScroll()
            return
        }
        this.highlighted_id = showed[showed.indexOf(this.highlighted_id) + 1]
        this.dropdownScroll()
    },
    decrementHighlight() {
        var showed = this.getShowed()
        if (this.onlyOneElement()) { this.highlighted_id = showed[0]; return }
        if (this.getHigh() == showed[0]) {
            this.highlighted_id = showed[showed.length - 1]
            this.dropdownScroll()
            return
        }
        this.highlighted_id = showed[showed.indexOf(this.highlighted_id) - 1]
        this.dropdownScroll()
    },
    updateElements() {
        this.showed = [];
        for (const item of Object.values(this.items)) {
            if (item[this.key]?.toLowerCase().includes(this.query.toLowerCase()) || this.query === '') {
                this.showed.push(item.id);
            }
        }
        this.dropdownScroll();
    },
    dropdownScroll() {
        dropdown_current = document.getElementById(this.name + '-dropdown-item-' + this.highlighted_id)
        if (dropdown_current) {
            dropdown_current.scrollIntoView({ behavior: 'smooth', block: 'center' })
        }
    }
}" class="relative" @click="open = true" @click.outside="open = false">
    <div class="flex flex-wrap gap-x-1">
        <template x-for="id_selected in selected" :key="id_selected">
            <flux:badge size="small" color="violet" class="text-xs mb-2">
                <span x-text="getItem(id_selected)[key]"></span>
                <flux:badge.close @click="removeSelected(id_selected)" />
            </flux:badge>
        </template>
    </div>
    <input {{ $attributes->only('class')->merge(['class' => $classes]) }} x-model='query'
        x-bind:class="open ? 'rounded-b-none border-b-0' : ''" x-on:focus="open = true" x-on:keydown.tab="open = false"
        x-on:input="open = true; updateElements()" x-on:keyup.up="decrementHighlight()"
        x-on:keyup.down="incrementHighlight()" x-on:keyup.enter="toggleSelected(highlighted_id)" />
    <div class="w-full z-20 flex flex-col text-sm items-stretch rounded-lg shadow-lg bg-white max-h-50 overflow-y-scroll rounded-t-none border-t-0 absolute border dark:bg-zinc-700 dark:border-zinc-800"
        x-show="open" x-id="['element']" id="dropdown-element">
        <template x-for="item_id in getShowed()" :key="name + '-' + item_id">
            <li x-bind:id="name + '-dropdown-item-' + item_id" class="{{ $class_element }}"
                x-bind:class="item_id == highlighted_id ? 'bg-zinc-200 dark:bg-zinc-600' : ''"
                @click="toggleSelected(item_id)">
                <flux:icon.check x-show="selected.includes(item_id)" variant="micro" />
                <span x-text="getItem(item_id)[key]"></span>
            </li>
        </template>
        <li class="{{ $class_element }}" x-show="getShowed() == 0">Pas de résultats</li>
    </div>
</div>
