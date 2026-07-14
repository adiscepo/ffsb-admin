<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'FFSB Admin') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 items-end flex-col h-screen">
    <header class="flex justify-center items-center">
    </header>
    <main class="w-full h-full flex justify-center gap-10 items-center flex-col">
        <span class="flex mb-1 items-center justify-center bg-accent rounded-full ">
            <x-app-logo-icon class="size-42 m-6 text-white fill-current dark:text-white" />
        </span>
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-[#1915014a] hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                        Se connecter
                    </a>

                    {{-- @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            S'inscrire
                            </a>
                        @endif --}}
                @endauth
            </nav>
        @endif
    </main>
    <div class="absolute top-0 right-0 w-30 max-sm:hidden">
        <img src="{{ url('/images/RideauFurtif.png') }}" class="rotate-y-180">
    </div>
    <div class="absolute top-0 left-5 w-70">
        <img src="{{ url('/images/HamacCoupe.png') }}" class="translate-y-[-15pt]">
    </div>
    <div class="absolute bottom-2 right-5 w-60 max-sm:hidden">
        <img src="{{ url('/images/Pouf.png') }}" class="">
    </div>
    <div class="absolute bottom-2 left-5 w-60  max-sm:hidden">
        <img src="{{ url('/images/Transat.png') }}" class="">
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
