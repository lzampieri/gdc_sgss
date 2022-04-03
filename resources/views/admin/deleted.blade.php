<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
    
    <h2>Giocatori eliminati</h2>
    <h3> {{ App\Models\User::onlyTrashed()->count() }} giocatori eliminati.</h3>
    <table class="table-auto m-8">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Data registrazione</th>
            <th>Data eliminazione</th>
        </tr>
    </thead>
    <tbody>
    @foreach ( App\Models\User::onlyTrashed()->get() as $item )
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>
                {{ $item->created_at }}
            </td>
            <td>
                {{ $item->deleted_at }}
            </td>
            <td>
                <a class="ib fa-solid fa-trash-arrow-up tooltiper" href="{{ route('user.detrash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Ripristina</div></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>


    <h2 class="text-red">Eventi eliminati</h2>
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
    @foreach ( App\Models\Event::onlyTrashed()->with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
        <tr>
            
            <td>{{ $item->created_at }}</td>
            <td>
                {{ $item->theactor ? $item->theactor->name : '<span class="text-red">Ignoto</span>' }}
            </td>
            <td>
                {{ $item->thetarget ? $item->thetarget->name : '<span class="text-red">Ignoto</span>' }}
            </td>
            <td>
                @if ( $item->finalstate )
                    <span class="text-green">Vivo</span>
                @else
                    <span class="text-red">Morto</span>
                @endif
            </td>
            <td>
                <a class="ib fa-solid fa-trash-arrow-up tooltiper" href="{{ route('event.detrash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Ripristina</div></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    </table>
</x-layouts.main>