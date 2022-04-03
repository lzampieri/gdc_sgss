@php
    $teams = App\Models\Team::with(['users'])->get();
    $outFromTeams = App\Models\User::where(['isadmin' => False, 'team' => null])->get();
@endphp

<x-layouts.main>
    <div class="flex flex-row gap-4">
        <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
        <a class="button" href="{{ route('admin.cycles.teams') }}">Ciclo</a>
    </div>
    <div class="w-full grid grid-cols-4 grid-flow-row-dense">
        <div class="card flex flex-col items-center">
            <h2>Giocatori fuori dalle squadre</h2>
            <div class="flex flex-col items-center team" data-id="{{ -1 }}">
                @foreach ($outFromTeams as $u)
                    @if ($u->isalive)
                        <div class="border-2 border-main rounded-full px-4 py-2 player" data-id="{{ $u->id }}">{{ $u->name }}</div>
                    @endif
                @endforeach
            </div>
        </div>
        @foreach ($teams as $t)
        <div class="card flex flex-col items-center">
            <h2>Squadra {{ $t->id }}</h2>
            <div class="flex flex-col items-center team min-h-[100px] w-full" data-id="{{ $t->id }}">
                @foreach ($t->users as $u)
                    @if ($u->isalive)
                        <div class="border-2 border-main rounded-full px-4 py-2 player" data-id="{{ $u->id }}">{{ $u->name }}</div>
                    @endif
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex flex-row gap-4">
        <a class="button" onclick="send()">Salva</a>
        <a class="button" onclick="sendAndNew()">Aggiungi squadra</a>
    </div>

    <form method="POST" action="" id="theform">
        @csrf
        <input type="hidden" value="0" id="squads" name="squads" />
        <input type="hidden" value="0" id="newteam" name="newteam" />
    </form>
    
    <script type="text/javascript">
        var teams = $('.team');
        teams.each( ( i, t ) => {
            Sortable.create( t, { group: 'teams' } );
        });

        function compileAndSubmit( newsquad ) {
            var assoc = {};
            var players = $('.player');
            players.each( ( i, t ) => {
                assoc[ $(t).data('id') ] = $(t).parent('.team').data('id');
            });
            $('#squads')[0].value = JSON.stringify( assoc );
            $('#newteam')[0].value = newsquad;
            $('#theform').submit();
        }

        function send() {
            compileAndSubmit( 0 );
        }

        function sendAndNew() {
            compileAndSubmit( 1 );
        }

    </script>

</x-layouts.main>