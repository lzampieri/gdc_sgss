@php
$email = old( 'email', session( 'email' ) );
@endphp

<x-layouts.main>
    @if ( App\Http\Controllers\Settings::obtain( 'signup_enabled' ) == 1 ) 
        @if ( $email != null  )
            @if ( session( 'error' ) != null )
                <div class="bad-news">
                    {{ session( 'error' ) }}
                </div>
            @endif
            <form method="POST" action="" class="flex flex-col items-center w-full md:w-2/5">
                @csrf

                <label class="mt-6">Email</label>
                <input type="email" name="email" id="email" value="{{ $email }}" class="w-full mt-2 opacity-50" readonly/>
                @error( 'email' )
                    <span class="text-red"> {{ $message }} </span>
                @enderror

                <label class="mt-6">Nome</label>
                <input type="text" name="name" id="name" class="w-full mt-2" value="{{ old('name', null ) }}"/>
                @error( 'name' )
                    <span class="text-red"> {{ $message }} </span>
                @enderror

                <label class="mt-6">Data di nascita</label>
                <input type="date" name="birthday" id="birthday" class="w-full mt-2" value="{{ old('birthday', null ) }}"/>
                @error( 'birthday' )
                    <span class="text-red"> {{ $message }} </span>
                @enderror

                <span class="mt-6"><input type="checkbox" name="conditions" id="conditions" />
                Accetto i <a href="{{ route('terms'); }}" target="blank">termini e le condizioni</a> del gioco.</span>
                @error( 'conditions' )
                    <span class="text-red"> {{ $message }} </span>
                @enderror

                <input type="submit" class="button-large my-4" value="Registrati" />
            </form>
        @else 
            <div class="bad-news">
                Errore. Seguire la prevista procedura.
            </div>
            <x-items.login_button />
        @endif
    @else
        <div class="bad-news">
            Le iscrizioni sono chiuse.
        </div>
    @endif
</x-layouts.main>