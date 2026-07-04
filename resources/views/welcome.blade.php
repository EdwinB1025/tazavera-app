<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="tz-main">
    <x-welcome-navbar />
    <div class="h-[70%] w-[80%] flex items-stretch overflow-hidden relative">
        {{-- IZQUIERDA: Texto --}}
        <div class="flex-1 flex flex-col justify-center gap-9">
            <span class="tz-subtitle2">LA GUIA DEL BUEN CAFÉ</span>

            <flux:heading>
                Tu café, el que <span class="tz-accent">merecen</span> cada sorbo
            </flux:heading>

            <p class="self-stretch tz-paragraph w-full">
                Descubre, evalúa y colecciona los mejores cafés de especialidad —
                respaldados por evaluaciones objetivas basadas en la metodología SCA
                de quienes de verdad entienden de café.
            </p>

            <div>
                <flux:button variant="primary">Explorar cafés</flux:button>
            </div>
        </div>

        {{-- DERECHA: Imagen --}}
        <div class="flex-1 flex items-end justify-end overflow-hidden">
            <img
                src="{{ asset('images/coffee-beans.png') }}"
                alt="..."
                class="w-full h-auto object-contain scale-90 origin-bottom-right translate-x-10 translate-y-15" />
        </div>
    </div>
    <x-welcome-filterbar />
</body>

</html>