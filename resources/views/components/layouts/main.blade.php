<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-layouts.header />
    <body>
        <div class="
            w-full h-[10%]
            fixed bottom-0
            bg-[url('../assets/img/typewriter.png')]
            bg-bottom bg-no-repeat
            bg-[length:auto_100%]
            "
            ></div>
        <div class="w-full p-4">
            <img class="max-h-[250px] max-w-full m-auto" src="{{ asset( 'images/logo.png' ); }}" />
        </div>
        
        <div class="w-full flex flex-col items-center mb-36">
            <x-layouts.nav />
            <x-layouts.messagges />
            {{ $slot }}
        </div>
    </body>
</html>
