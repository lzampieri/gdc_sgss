<x-layouts.main>
    <x-items.communications />

    @auth
        <div class="card">
            Benvenuto {{ Auth::user()->name }}. <br />
            @if ( Auth::user()->is_alive )
                Al momento sei vivo. Avanti così! <br />
                Sfodera il tuo cucchiaio!
            @else
                Sei morto.<br/>
                Addio, ci rivediamo alla prossima edizione.
            @endif
        </div>

        @if ( Auth::user()->is_alive )
            @if ( Auth::user()->is_pending )
                <x-logic.pendings />
            @else
                <x-logic.my_team />
                <x-logic.targets />
            @endif
        @endif

        @if ( Auth::user()->isadmin )
            <a class="text-center button" href="{{ route( 'admin.main' ) }}">
                Admin
            </a>
        @endif
    @endauth

    @guest
  		<div class="card md:w-2/5">
        	Un antico manoscritto è stato trovato. Partecipa anche tu al Gioco del Cucchiaio, usando la nobile posata come inteso dalla venerabile Società della Ligula.
  		</div>
        <x-items.login_button />
    @endguest

    <div class="flex flex-row flex-wrap items-center justify-center">
        <a class="text-center button" href="{{ route( 'regolamento' ) }}">
            Regolamento
        </a>
        <a class="text-center button" href="{{ route( 'albo-doro' ) }}">
            Albo d'oro
        </a>
        @auth
            <a class="text-center button" href="{{ route( 'signoff' ) }}">
                Logout
            </a>    
        @endauth
    </div>

    {{-- <a href="{{ route('about') }}">About</a> --}}
</x-layouts.main>