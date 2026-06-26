@props([
    'src' => null,
])

<div>
    <flux:modal name="image-{{ sha1($src) }}">
        <div class="flex flex-col">
            <img src="{{ $src }}" alt="" />
            <a href="{{ $src }}" download class="self-end">
                <flux:icon icon="arrow-down-tray" />
            </a>
        </div>
    </flux:modal>

    <flux:modal.trigger name="image-{{ sha1($src) }}">
        <img src="{{ $src }}" {{ $attributes->only('class')->merge(['class' => 'w-full']) }} />
    </flux:modal.trigger>
</div>
