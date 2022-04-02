<x-layouts.main>
    @auth
        <div class="card">
            Benvenuto {{ Auth::user()->name }}. <br />
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