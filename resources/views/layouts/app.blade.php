<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen tz-bg-inverse">
    <x-layouts::app.header />
    <x-layouts::app.sidebar class="top-0 max-h-dvh h-full" />

    <flux:main class="mx-[5%] my-[5%]">{{ $slot }}</flux:main>
    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist
    @fluxScripts
</body>

</html>