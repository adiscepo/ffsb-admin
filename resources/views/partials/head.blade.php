<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf_token" value="{{ csrf_token() }}"/>

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'FFSB Admin') : config('app.name', 'FFSB Admin') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
