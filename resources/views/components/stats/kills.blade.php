<div class="card w-full md:w-3/5">
    <div class="w-full text-center text-xs">Clicca sui nomi delle colonne per ordinare</div>
    <table id="kills_table" class="w-full">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Compleanno</th>
                <th>Stato</th>
                <th>Assassini</th>
                <th>Grazie</th>
                <th>Morti</th>
                <th>Resurrezioni</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    @php
        $users = App\Models\User::where( 'isadmin', False )->get();
    @endphp
    var users_data = [
        @foreach ( $users as $user )
            {
                id: {{ $user->id }},
                name: '{{ $user->name }}',
                email: '{{ $user->email }}',
                birthday: '{{ \Carbon\Carbon::parse( $user->birthday )->format( 'd-m') }}',
                is_alive: {{ $user->is_alive ? 'true' : 'false' }},
                kills: {{ $user->events_done()->where( 'finalstate', False )->count() }},
                jesus: {{ $user->events_done()->where( 'finalstate', True )->count() }},
                deaths: {{ $user->events_suffered()->where( 'finalstate', False )->count() }},
                lazarus: {{ $user->events_suffered()->where( 'finalstate', True )->count() }}
            },
        @endforeach
    ]
    var dt_api = $('#kills_table').DataTable( {
        data: users_data,
        columns: [
            { data: 'name', name: 'Nome' },
            { data: 'birthday', render: ( data, type, row ) => ( type == 'sort' ? data.slice(-2)  + data : data ) },
            { data: 'is_alive', render: ( data, type, row ) => ( data ? '<span class="text-green">Vivo</span>' : '<span class="text-red">Morto</span>' ) },
            { data: 'kills' },
            { data: 'jesus' },
            { data: 'deaths' },
            { data: 'lazarus' },
        ],
        columnDefs: [
            
        ],
        paging: false,
        searching: false,
        info: false,
        fixedHeader: true
    } );
</script>