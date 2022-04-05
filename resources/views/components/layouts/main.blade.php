<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-layouts.header />
    <body>
        <div class="w-full p-4">
            <img class="max-h-[250px] max-w-full m-auto" src="{{ asset( 'images/logo.png' ); }}" />
        </div>
        
        <div class="w-full flex flex-col items-center mb-36 gap-4">
            <x-layouts.nav />
            <x-layouts.messagges />
            {{ $slot }}
        </div>
    </body>
</html>
