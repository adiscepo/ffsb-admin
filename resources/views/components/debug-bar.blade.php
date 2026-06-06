@props([
    'position' => 'top',
])

<div class="fixed w-full {{ $position }}-0 z-50 bg-yellow-400 h-1 border-box shadow-lg">
    <div class="absolute {{ $position }}-0 right-4 shadow-lg z-50 px-2 bg-yellow-400 border-box">
        <span class="font-bold text-black">🔨 Version dev</span>
    </div>
</div>
