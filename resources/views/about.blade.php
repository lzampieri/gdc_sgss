<x-layouts.main>
    <a class="text-center button" href="{{ route( 'home' ) }}">
        Home
    </a>
    <a class="card flex flex-col items-center no-underline" href="https://github.com/lzampieri/gdc_sgss">
        Tutto il codice è disponibile su
        <div class="flex flex-row items-center h-16">
            <img class="max-h-full" src="{{ asset( 'images/github_cat.png' ); }}" />
            <img class="max-h-full" src="{{ asset( 'images/github_text.png' ); }}" />
        </div>
    </a>
</x-layouts.main>