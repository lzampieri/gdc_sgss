<x-layouts.main>
    @auth
        <div class="card">
            Benvenuto {{ Auth::user()->name }}. <br />
            @if ( Auth::user()->isalive )
                Al momento sei vivo. Avanti così! <br />
                Sfodera il tuo cucchiaio!
                <x-logic.targets />
            @else
                Sei morto.<br/>
                Addio, ci rivediamo alla prossima edizione.
            @endif
        </div>
    @endauth

    @guest
        <x-items.login_button />
    @endguest

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
</x-layouts.main>