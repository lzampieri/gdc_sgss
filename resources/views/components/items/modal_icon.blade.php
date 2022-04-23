@props(['icon', 'url', 'event'])

<span x-data="{ open: false }">
    <a class="ib text-2xl {{ $icon }}" @click="open = true" ></a>
    <div
        class="fixed z-40 bg-bgblack opacity-50 inset-0"
        x-show="open"
        >
    </div>
    <div
        class="fixed inset-0 z-50 flex items-center justify-center"
        x-show="open"
        @click="open = false">
        <div class="card bg-bg flex flex-col justify-center gap-4" @click.stop>
            <h2>Sei sicuro?</h2>
            <p>Confermi di {{ $event }}?</p>
            <div class="flex flex-row justify-center">
                <a class="button" @click="open = false">Annulla</a>
                <a class="button" href="{{ $url }}">Conferma</a>
            </div>
        </div>
    </div>
</span>