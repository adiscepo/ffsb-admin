@props(['route' => 'Example/Route', 'separator' => '/', 'bold' => -1])

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 dark:border-zinc-700 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <a href="{{ URL::previous() }}" wire:navigate class="text-zinc-500"><flux:icon.chevron-left
                        variant="micro" /></a>
                @php
                    $splitted_route = explode($separator, $route);
                @endphp
                @foreach ($splitted_route as $i => $part)
                    @php
                        if ($bold == -1) {
                            $is_bold = count($splitted_route) - 1 == $i;
                        } else {
                            $is_bold = $bold == $i;
                        }
                    @endphp
                    @if ($i < count($splitted_route) - 1)
                        <span
                            class="@if ($is_bold) font-bold @else text-zinc-500 @endif">{{ $part }}</span>
                        <span class="text-zinc-500">/</span>
                    @else
                        <span
                            class="@if ($is_bold) font-bold @else text-zinc-500 @endif">{{ $part }}</sptext-zinc-500an>
                    @endif
                @endforeach
            </div>
        </nav>
        {{ $slot }}
    </header>
</x-slot>
