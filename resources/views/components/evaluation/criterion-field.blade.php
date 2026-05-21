@props([
    'name' => $name,
    'description' => $description,
    'note' => null,
    'comment' => null,
])

<div class="grid grid-cols-[2fr_1fr] md:grid-cols-[2fr_1fr_3fr] gap-y-2 md:gap-y-5 md:gap-0 items-center">
    <div class="space-y-1">
        {{-- Need to use !! to display the content without htmlspecialchars --}}
        <p class="text-xs md:text-base font-medium text-zinc-800 dark:text-zinc-300">{!! $name !!}</p>
        <p class="text-xs text-zinc-400">{!! $description !!}</p>
    </div>
    <div x-data="{
        note: '',
        checkNote() {
            if (this.note == '') return;
            this.note = parseInt(this.note)
            if (!this.note && this.note != 0) this.note = ''
            if (this.note < 0 || this.note > 6) {
                this.note = '';
                return;
            }
        },
        getColor() {
            switch (this.note) {
                case 0:
                    return 'bg-red-400 text-red-900';
                case 1:
                    return 'bg-orange-400 text-orange-900';
                case 2:
                    return 'bg-amber-400 text-amber-900';
                case 3:
                    return 'bg-yellow-400 text-yellow-900';
                case 4:
                    return 'bg-lime-400 text-lime-900';
                case 5:
                    return 'bg-green-400 text-green-900';
                case 6:
                    return 'bg-purple-400 text-purple-900';
            }
        }
    }" class="justify-self-end md:justify-self-center">
        <input class="w-15 h-15 md:w-20 md:h-20 border dark:border-zinc-600 text-center md:font-medium md:text-xl rounded" type="text" value="{{ isset($note) ? $note : '' }}"
            step="1" min="0" max="6" name="" id="" x-model="note"
            x-on:keyup='checkNote()' x-bind:value="note" x-bind:class="getColor()">
    </div>
    <textarea name="" id="" cols="0" rows="0"
        class="w-full h-full p-2 rounded border resize-none dark:border-zinc-600 text-sm focus:outline-none col-span-2 md:col-span-1">{{ isset($comment) ? $comment : '' }}</textarea>
</div>
