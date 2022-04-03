<x-layouts.main>
    <a class="button" href="{{ route('home') }}">Home</a>
    <h2>Giocatori</h2>
    <h3> {{ App\Models\User::where( 'isadmin', False )->count() }} giocatori iscritti</h3>
    <h3> {{ App\Models\User::where( 'isadmin', False )->get()->filter( function ($i) { return $i->isalive; })->count() }} giocatori vivi</h3>
    <table class="table-auto m-8">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Stato</th>
            <th>Data registrazione</th>
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
        </tr>
    @endforeach
    </tbody>
    </table>

    
    <h2>Amministratori</h2>
    <h3> {{ App\Models\User::where( 'isadmin', True )->count() }} amministratori</h3>
    <table class="table-auto m-8">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Stato</th>
            <th>Data registrazione</th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\User::where( 'isadmin', True )->get() as $item )
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
        </tr>
    @endforeach
    </tbody>
    </table>

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

    <h2 class="mt-8 ">Eventi</h2>
    <table class="table-auto m-8">
    <thead>
        <tr>
            <th>Data</th>
            <th>Agente</th>
            <th>Subente</th>
            <th>Stato finale del subente</th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\Event::with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
        <tr>
            
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->theactor->name }}</td>
            <td>{{ $item->thetarget->name }}</td>
            <td>
                @if ( $item->finalstate )
                    <span class="text-green">Vivo</span>
                @else
                    <span class="text-red">Morto</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
    <h2 class="mt-8 text-red">Eventi eliminati</h2>
    <table class="table-auto m-8">
    <thead>
        <tr>
            <th>Data</th>
            <th>Agente</th>
            <th>Subente</th>
            <th>Stato finale del subente</th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\Event::onlyTrashed()->with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
        <tr>
            
            <td>{{ $item->created_at }}</td>
            <td>{{ $item->theactor->name }}</td>
            <td>{{ $item->thetarget->name }}</td>
            <td>
                @if ( $item->finalstate )
                    <span class="text-green">Vivo</span>
                @else
                    <span class="text-red">Morto</span>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
</x-layouts.main>