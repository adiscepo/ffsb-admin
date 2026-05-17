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

    public int $current_month;
    public int $current_year;

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
        $this->current_month = 5;
        $this->current_year = 2026;
        $this->getCurrentMonth($this->current_month, $this->current_year);
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

<div class="relative" x-on:click.outside='open = false' x-data="{
    query: '',
    open: true,
    selected_date: @js('1/' . $this->current_month . '/' . $this->current_year),
    current_month: @js($this->getCurrentMonth($this->current_month, $this->current_year)),
    min_date: '17/05/2025',
    max_date: '17/05/2034',
    getDay(date) {
        if (date) return parseInt(date.split('/')[0])
    },
    getMonth(date) {
        if (date) return parseInt(date.split('/')[1])
    },
    getYear(date) {
        if (date) return parseInt(date.split('/')[2])
    },
    changeMonth(month, year) {
        if (this.isFirstMonth(month, year)) {
            month = this.getMonth(this.min_date)
            year = this.getYear(this.min_date)
        } else if (this.isLastMonth(month, year)) {
            month = this.getMonth(this.max_date)
            year = this.getYear(this.max_date)
        }
        $wire.getCurrentMonth(month, year).then((res) => {
            this.current_month = res
        })
    },
    isFirstMonth(month, year) {
        return (month == this.getMonth(this.min_date) && year == this.getYear(this.min_date))
    },
    isLastMonth(month, year) {
        return (month == this.getMonth(this.max_date) && year == this.getYear(this.max_date))
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
    isValid(date) {
        var month = this.getMonth(date)
        var year = this.getYear(date)
        if (year > this.getYear(this.min_date)) {
            return true
        } else if (month >= this.getMonth(this.min_date)) {
            return true
        }
        return false
    },
    selectDate(date) {
        // Rewrite the date with leading 0 (ex: 5 -> 05)
        date = (this.getDay(date).toString().padStart(2, '0')) + '/' + (this.getMonth(date).toString().padStart(2, '0')) + '/' + (this.getYear(date))
        if (this.isValid(date)) {
            console.log('Selected: ' + date)
            var month = this.current_month['month']
            var year = this.current_month['year']
            var selected_month = this.getMonth(date)
            var selected_year = this.getYear(date)
            this.changeMonth(selected_month, selected_year)
            this.selected_date = date;
        }
    },
    selectYear(year) {
        if (year == this.getYear(this.max_date)) {
            if (this.current_month['month'] >= this.getMonth(this.max_date)) {
                this.changeMonth(this.getMonth(this.max_date), year)
                return
            }
        } else if (year == this.getYear(this.min_date)) {
            if (this.current_month['month'] <= this.getMonth(this.min_date)) {
                this.changeMonth(this.getMonth(this.min_date), year)
                return
            }
        } else {
            console.log('Pas trop loin')
        }
        this.changeMonth(this.current_month['month'], year)
    },
    isSelected(date) {
        return date == this.selected_date;
    },
    getYearsInterval() {
        var min_year = parseInt(this.min_date.split('/')[2])
        var max_year = parseInt(this.max_date.split('/')[2])
        years = []
        while (min_year <= max_year) {
            years.push(min_year);
            min_year += 1;
        }
        return years;
    }
}">
    <div class="{{ $classes }} cursor-pointer flex gap-x-2" 
        x-bind:class="open ? 'rounded-b-none border-b-0' : ''"><flux:icon.calendar-days variant="mini"
            class="text-zinc-400" />
        {{-- <span x-text="selected_date"></span> --}}
        <input type="text" @click="open = true" @keyup.enter="selectDate(selected_date)" x-model='selected_date'
            x-bind:value="selected_date" />
    </div>
    <div class="w-full flex flex-col text-sm items-stretch rounded-lg shadow-lg bg-white overflow-y-scroll rounded-t-none border-t-0 absolute border p-3 space-y-5"
        x-show="open">
        <div class="flex justify-between">
            <div>
                <span x-text="current_month['month_text']"></span>
                <select>
                    <template x-for="year in getYearsInterval()">
                        <template x-if="true">
                            <option x-text="year" x-bind:value="year"
                                x-bind:selected="year == current_month['year']" @click="selectYear(year)" />
                        </template>
                    </template>
                </select>
            </div>
            <div class="flex">
                <flux:icon.chevron-left variant="mini"
                    x-show="!isFirstMonth(current_month['month'], current_month['year'])" class="{{ $class_clickable }}"
                    @click="decrementMonth()" />
                <flux:icon.chevron-right variant="mini"
                    x-show="!isLastMonth(current_month['month'], current_month['year'])" class="{{ $class_clickable }}"
                    @click="incrementMonth()" />
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
                <div class="{{ $class_clickable }} w-8 h-8 text-center flex items-center justify-center"
                    x-bind:class="isSelected(date['path']) ? 'bg-violet-400 text-white hover:bg-violet-400!' : ''"
                    @click="selectDate(date['path'])">
                    <span x-text="date['day']"
                        x-bind:class="date['month'] == current_month['month'] ? '' : 'text-zinc-400';"></span>
                </div>
            </template>
        </div>
    </div>
</div>
