@php
    $teams = App\Models\Team::with(['users'])->get();
    $outFromTeams = App\Models\User::where(['isadmin' => False, 'team' => null])->get();
@endphp

<x-layouts.main>
    <div class="flex flex-row gap-4">
        <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
        <a class="button" href="{{ route('admin.cycles.teams') }}">Ciclo</a>
    </div>
    <span>Trascina gli elementi per spostarli di squadra.</span>
    <div class="w-full grid grid-cols-1 md:grid-cols-4 grid-flow-row-dense">
        <div class="card flex flex-col items-center">
            <h2>Giocatori fuori dalle squadre</h2>
            <div class="flex flex-col items-center team min-h-[50px] w-full" data-id="{{ -1 }}">
                @foreach ($outFromTeams as $u)
                    @if ($u->is_alive)
                        <div class="border-2 border-main rounded-full px-4 py-2 player" data-id="{{ $u->id }}" data-bossof="{{ $u->is_team_boss ? $t->id : -2 }}">
                            <i @class([ 'fa-solid fa-hat-wizard', 'force-hidden' => !($u->is_team_boss) ]) ></i>
                            {{ $u->name }}
                            <a @class([ 'ib fa-solid fa-hat-wizard tooltiper opacity-40 hover:opacity-100', 'force-hidden' => ($u->is_team_boss) ]) onclick="sendAndTeamBoss({{ $u->id }})"><div class="tooltip">Rendi caposquadra</div></a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        @foreach ($teams as $t)
        <div class="card flex flex-col items-center">
            <h2>Squadra {{ $t->id }}</h2>
            <div class="flex flex-col items-center team min-h-[50px] w-full" data-id="{{ $t->id }}">
                @foreach ($t->users as $u)
                    @if ($u->is_alive)
                        <div class="border-2 border-main rounded-full px-4 py-2 player" data-id="{{ $u->id }}" data-bossof="{{ $u->is_team_boss ? $t->id : -2 }}">
                            <i @class([ 'fa-solid fa-hat-wizard', 'force-hidden' => !($u->is_team_boss) ]) ></i>
                            {{ $u->name }}
                            <a @class([ 'ib fa-solid fa-hat-wizard tooltiper opacity-40 hover:opacity-100', 'force-hidden' => ($u->is_team_boss) ]) onclick="sendAndTeamBoss({{ $u->id }})"><div class="tooltip">Rendi caposquadra</div></a>
                        </div>
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
        <input type="hidden" value="-1" id="newteamboss" name="newteamboss" />
    </form>
    
    <script type="text/javascript">
        var teams = $('.team');
        teams.each( ( i, t ) => {
            Sortable.create( t, { group: 'teams', onEnd: ( evt ) => updateAdminIcons( evt.item ) } );
        });

        function compileAndSubmit( newsquad, newteamboss ) {
            var assoc = {};
            var players = $('.player');
            players.each( ( i, t ) => {
                assoc[ $(t).data('id') ] = $(t).parent('.team').data('id');
            });
            $('#squads')[0].value = JSON.stringify( assoc );
            $('#newteam')[0].value = newsquad;
            $('#newteamboss')[0].value = newteamboss;
            $('#theform').submit();
        }

        function updateAdminIcons( el ) {
            let bossof = $(el).data('bossof');
            let newteam = $(el).parent('.team').data('id');
            let is_boss = ( bossof == newteam );
            let is_boss_indicator = $( $(el).children('i')[0] );
            let is_boss_button = $( $(el).children('a')[0] );

            if( is_boss ) {
                is_boss_indicator.removeClass( 'force-hidden' );
                is_boss_button.addClass( 'force-hidden' );
            } else {
                is_boss_indicator.addClass( 'force-hidden' );
                is_boss_button.removeClass( 'force-hidden' );
            }   
        }

        function send() {
            compileAndSubmit( 0, -1 );
        }

        function sendAndNew() {
            compileAndSubmit( 1, -1 );
        }

        function sendAndTeamBoss( tb ) {
            compileAndSubmit( 0, tb );
        }

    </script>

</x-layouts.main>