<x-layouts.main>
    <a class="text-center button" href="{{ route( 'home' ) }}">
        Home
    </a>
    <a class="card flex flex-col items-center no-underline" href="https://github.com/lzampieri/gdc_sgss">
        Tutto il codice è disponibile su
        <div class="flex flex-row items-center h-16">
            <img class="max-h-full" src="{{ asset( 'images/about/github_cat.png' ); }}" />
            <img class="max-h-full" src="{{ asset( 'images/about/github_text.png' ); }}" />
        </div>
    </a>
    <div class="card text-center">
        Progettazione e realizzazione di <a href="https://github.com/lzampieri/">L. Zampieri</a>.<br />
        Logo di D. Baffelli.
    </div>
    @php
        $frameworks = [
            asset( 'images/about/laravel.png' ) => 'http://laravel.com/',
            asset( 'images/about/tailwindcss.svg' ) => 'https://tailwindcss.com/',
            asset( 'images/about/laravel-excel.svg' ) => 'https://laravel-excel.com/',
            asset( 'images/about/fontawesome.png' ) => 'https://fontawesome.com/',
            asset( 'images/about/jquery.png' ) => 'https://jquery.com/',
            asset( 'images/about/alpinejs.svg' ) => 'https://alpinejs.dev/',
            asset( 'images/about/sortableJS.PNG' ) => 'http://sortablejs.github.io/Sortable/',
            asset( 'images/about/ibm-plex-mono.png' ) => 'https://fonts.google.com/specimen/IBM+Plex+Mono',
            asset( 'images/about/vscode.png' ) => 'https://code.visualstudio.com/',
            asset( 'images/about/inkscape.png' ) => 'https://inkscape.org/',
        ]
    @endphp
    <div class="card text-center w-full md:w-3/5">
        Sviluppato con:
        <div class="grid grid-cols-1 md:grid-cols-3 grid-flow-row-dense w-full gap-4 items-center">
            @foreach ($frameworks as $img => $url)
                <a class="max-w-full" href="{{$url}}">
                    <img class="max-w-full max-h-32 mx-auto" src="{{ $img }}" />
                </a>
            @endforeach
        </div>
    </div>
</x-layouts.main>