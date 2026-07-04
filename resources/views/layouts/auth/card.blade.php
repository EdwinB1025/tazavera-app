<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased">
    <div class="tz-bg-inverse flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm sm:max-w-lg flex-col gap-6">
            <a href="{{ route('landing') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                <span class="flex h-full w-full mb-1 items-center justify-center rounded-md">
                    <x-logo.app-logo class="size-20 fill-current" />
                </span>

                <span class="sr-only">{{ config('app.name', 'TAZAVERA') }}</span>
            </a>

            <div class="flex flex-col gap-6">
                <div class="tz-logincard rounded-xl border bg-white text-stone-800 shadow-xs">
                    <div class="px-10 py-8">{{ $slot }}</div>
                </div>
            </div>
        </div>
    </div>

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>