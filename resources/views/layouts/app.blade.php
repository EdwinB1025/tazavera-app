<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="tz-bg-inverse">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>