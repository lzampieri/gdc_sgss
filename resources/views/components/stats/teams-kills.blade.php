<div class="card w-full md:w-3/5">
    <div class="w-full text-center text-xs">Clicca sui nomi delle colonne per ordinare</div>
    <table id="teams_kills_table" class="w-full">
        <thead>
            <tr>
                <th>Componenti</th>
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
        $teams = App\Models\Team::get();
    @endphp
    var teams_data = [
        @foreach ( $teams as $team )
            @php
                $users = $team->users()->where( 'isadmin', False )->get();
                $names = "";
                $kills = 0;
                $jesus = 0;
                $deaths = 0;
                $lazarus = 0;
                foreach( $users as $user ) {
                    $color = $user->is_alive ? 'text-green' : 'text-red';
                    $names .= "<span class='" . $color . "'>" . $user->name . "</span>, ";
                    $kills += $user->events_done()->where( 'finalstate', False )->count();
                    $jesus += $user->events_done()->where( 'finalstate', True )->count();
                    $deaths += $user->events_suffered()->where( 'finalstate', False )->count();
                    $lazarus += $user->events_suffered()->where( 'finalstate', True )->count();
                }
                $names = substr( $names, 0, -2 );
                if( $names == "" ) {
                    $names = "Nessuno";
                }
            @endphp
            {
                names: "{!! $names !!}",
                kills: {{ $kills }},
                jesus: {{ $jesus }},
                deaths: {{ $deaths }},
                lazarus: {{ $lazarus }}
            },
        @endforeach
    ]
    var dt_api = $('#teams_kills_table').DataTable( {
        data: teams_data,
        columns: [
            { data: 'names', name: 'Nomi' },
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