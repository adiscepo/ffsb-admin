<?php
use Livewire\Component;
use Carbon\CarbonImmutable;
use App\Models\ProductionHouse;

new class extends Component {
    public string $query = '';
    public array $selected = [];

    public array $datas = [];
    public array $results = [];

    public int $highlight_id = 0;

    private array $months = [
        1 => 'Janvier',
        2 => 'Février',
        3 => 'Mars',
        4 => 'Avril',
        5 => 'Mai',
        6 => 'Juin',
        7 => 'Juillet',
        8 => 'Août',
        9 => 'Septembre',
        10 => 'Octobre',
        11 => 'Novembre',
        12 => 'Décembre',
    ];

    public function mount()
    {
        // $this->datas = $datas;
        $this->getCurrentMonth(5, 2026);
    }

    public function getCurrentMonth($month, int $year)
    {
        $startOfMonth = CarbonImmutable::create($year, $month, 1);
        $endOfMonth = $startOfMonth->endOfMonth();
        $startOfWeek = $startOfMonth->startOfWeek();
        $endOfWeek = $endOfMonth->endOfWeek();

        $res = [
            'year' => $startOfMonth->year,
            'month' => $startOfMonth->month,
            'month_text' => $this->months[$startOfMonth->month],
            'dates' => collect(collect($startOfWeek->toPeriod($endOfWeek)->toArray()))->map(
                fn($date) => [
                    'path' => $date->format('d/m/Y'),
                    'day' => $date->day,
                    'month' => $date->month,
                ],
            ),
        ];
        return $res;
    }
};
?>

@php
    $classes =
        'w-full border rounded-lg block disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-[1.375rem] ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-zinc-700 disabled:text-zinc-500 placeholder-zinc-400 disabled:placeholder-zinc-400/70 dark:text-zinc-300 dark:disabled:text-zinc-400 dark:placeholder-zinc-400 dark:disabled:placeholder-zinc-500 shadow-xs border-zinc-200 border-b-zinc-300/80 disabled:border-b-zinc-200 dark:border-white/10 dark:disabled:border-white/5 data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500 outline-none';
    $class_element = 'flex items-center gap-2 list-none p-2 w-full cursor-pointer [:where(&)]:hover:bg-zinc-200';
    $class_clickable = 'rounded cursor-pointer hover:bg-zinc-100';
@endphp

<div class="relative" x-data="{
    query: '',
    open: true,
    selected_date: '',
    current_month: @js($this->getCurrentMonth(5, 2026)),
    changeMonth(month, year) {
        $wire.getCurrentMonth(month, year).then((res) => {
            this.current_month = res
        })
        console.log(this.current_month)
    },
    decrementMonth() {
        var new_month = this.current_month['month'] - 1
        var year = this.current_month['year']
        if (new_month < 1) {
            new_month = 12
            year -= 1
        }
        this.changeMonth(new_month, year)
    },
    incrementMonth() {
        var new_month = this.current_month['month'] + 1
        var year = this.current_month['year']
        if (new_month > 12) {
            new_month = 1
            year += 1
        }
        this.changeMonth(new_month, year)
    },
    selectDate(date) {
        var selected_month = date.split('/')[1]
        var month = this.current_month['month']
        if (selected_month == month + 1) {
            this.incrementMonth()
        } else if (selected_month == month - 1) {
            this.decrementMonth()
         }
        this.selected_date = date;
    },
    isSelected(date) {
        return date == this.selected_date;
    }
}">
    <div class="{{ $classes }} cursor-pointer flex gap-x-2" x-on:click="open = !open" 
        x-bind:class="open ? 'rounded-b-none border-b-0' : ''"><flux:icon.calendar-days variant="mini"
            class="text-zinc-400" />
        <span x-text="selected_date"></span>
    </div>
    <div class="w-full flex flex-col text-sm items-stretch rounded-lg shadow-lg bg-white overflow-y-scroll rounded-t-none border-t-0 absolute border p-3 space-y-5" x-on:click.outside='open = false'
        x-show="open">
        <div class="flex justify-between">
            <div>
                <span x-text="current_month['month_text']"></span>
                <span x-text="current_month['year']"></span>
            </div>
            <div class="flex">
                <flux:icon.chevron-left variant="mini" class="{{ $class_clickable }}" @click="decrementMonth()" />
                <flux:icon.chevron-right variant="mini" class="{{ $class_clickable }}" @click="incrementMonth()"  />
            </div>
        </div>
        <div class="grid grid-cols-7 grid-rows-5 gap-2 place-items-center justify-items-center">
            <span class="text-zinc-600">Lu</span>
            <span class="text-zinc-600">Ma</span>
            <span class="text-zinc-600">Me</span>
            <span class="text-zinc-600">Je</span>
            <span class="text-zinc-600">Ve</span>
            <span class="text-zinc-600">Sa</span>
            <span class="text-zinc-600">Di</span>
            <template x-for="date in current_month['dates']">
                <div class="{{ $class_clickable }} w-8 h-8 text-center flex items-center justify-center" x-bind:class="isSelected(date['path']) ? 'bg-violet-400 text-white hover:bg-violet-400!' : ''" @click="selectDate(date['path'])">
                    <span x-text="date['day']"
                        x-bind:class="date['month'] == current_month['month'] ? '' : 'text-zinc-400';"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- <div x-data="{
    query: '',
    open: true,
    highlighted_id: 1,
    selected: @js($this->selected),
    showed: [],
    items: @js($this->datas),
    getShowed() {
        if (this.query == '') this.updateElements()
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
        this.showed = []
        for (id in this.items) {
            if (this.items[id].name.toLowerCase().includes(this.query.toLowerCase()) || this.query == '') {
                this.showed.push(this.items[id].id)
            }
        }
        this.highlighted_text = this.showed[0] - 1
        this.dropdownScroll()
    },
    getDays() {
        dropdown_current = document.getElementById('dropdown-item-' + this.highlighted_id)
        if (dropdown_current) {
            dropdown_current.scrollIntoView({ behavior: 'smooth', block: 'center', })
        }
    }
}" class="relative" @click="open = true" @click.outside="open = false">
    <div class="flex flex-wrap gap-x-1">
        <template x-for="id_selected in selected" :key="id_selected">
            <flux:badge size="small" color="violet" class="text-xs mb-2">
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
        <template x-for="item in getDays()" :key="item">
        </template>
        <li class="{{ $class_element }} hover:bg-white" x-show="getShowed() == 0">Pas de résultats</li>
    </div>
</div> --}}
