<x-layouts.main>
    <a class="button" href="{{ route('home') }}">Home</a>
    <h2>Giocatori</h2>
    <h3> {{ App\Models\User::where( 'isadmin', False )->count() }} giocatori iscritti</h3>
    <h3> {{ App\Models\User::where( 'isadmin', False )->get()->filter( function ($i) { return $i->isalive; })->count() }} giocatori vivi</h3>
    <table class="table-auto">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Stato</th>
            <th>Data registrazione</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\User::where( 'isadmin', False )->get() as $item )
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>
                @if ( $item->isalive )
                    <span class="text-green">Vivo</span>
                @else
                    <span class="text-red">Morto</span>
                @endif
            </td>
            <td>
                {{ $item->created_at }}
            </td>
            <td>
                @if ( $item->isalive )
                    <a class="ib fa-solid fa-skull tooltiper" href="{{ route('user.kill', [ 'id' => $item->id ] ) }}"><div class="tooltip">Uccidi</div></a>
                @else 
                    <a class="ib fa-solid fa-cross tooltiper" href="{{ route('user.dekill', [ 'id' => $item->id ] ) }}"><div class="tooltip">Risorgi</div></a>
                @endif
                <a class="ib fa-solid fa-trash tooltiper" href="{{ route('user.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                <a class="ib fa-solid fa-toolbox tooltiper" href="{{ route('user.admin', [ 'id' => $item->id ] ) }}"><div class="tooltip">Promuovi</div></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>

    
    <h2>Amministratori</h2>
    <h3> {{ App\Models\User::where( 'isadmin', True )->count() }} amministratori</h3>
    <table class="table-auto">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data registrazione</th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\User::where( 'isadmin', True )->get() as $item )
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>
                {{ $item->created_at }}
            </td>
            <td>
                <a class="ib fa-solid fa-trash tooltiper" href="{{ route('user.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                <a class="ib fa-solid fa-hammer tooltiper" href="{{ route('user.deadmin', [ 'id' => $item->id ] ) }}"><div class="tooltip">Arretra</div></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>

    <a href="{{ route( 'admin.deleted' ) }}">Giocatori eliminati</a>

    <h2>Mailing lists</h2>
    <h3>Tutti i giocatori</h3>
    @foreach ( App\Models\User::where( 'isadmin', False )->get() as $item )
        {{ $item->email }},
    @endforeach
    
    <h3>Giocatori vivi</h3>
    @foreach ( App\Models\User::where( 'isadmin', False )->get() as $item )
        @if ( $item->isalive )
            {{ $item->email }},
        @endif
    @endforeach
    
    <h3>Amministratori</h3>
    @foreach ( App\Models\User::where( 'isadmin', True )->get() as $item )
        {{ $item->email }},
    @endforeach

    <h2>Eventi</h2>
    <table class="table-auto">
    <thead>
        <tr>
            <th>Data</th>
            <th>Agente</th>
            <th>Subente</th>
            <th>Stato finale del subente</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\Event::with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
        <tr>
            
            <td>{{ $item->created_at }}</td>
            <td>
                @if ( $item->theactor )
                    {{ $item->theactor->name }}
                @else
                    <span class="text-red">Ignoto</span>
                @endif
            </td>
            <td>
                @if ( $item->thetarget )
                    {{ $item->thetarget->name }}
                @else
                    <span class="text-red">Ignoto</span>
                @endif
            </td>
            <td>
                @if ( $item->finalstate )
                    <span class="text-green">Vivo</span>
                @else
                    <span class="text-red">Morto</span>
                @endif
            </td>
            <td>
                <a class="ib fa-solid fa-trash tooltiper" href="{{ route('event.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>

    <a href="{{ route( 'admin.deleted' ) }}">Eventi eliminati</a>
</x-layouts.main>