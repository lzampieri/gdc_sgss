@php
    $targets = App\Http\Controllers\Targets::targets();
@endphp

@if ( count( $targets ) == 0 )

    <div class="card mx-8">
        Non appena ti saranno assegnati degli obiettivi, compariranno qui. <br />
        Per il momento, non ti rimane altro che aspettare. <br />
        Aspetta, e prega.
    </div>

@elseif ( $targets[0] instanceof App\Models\User )

    <div class="card mx-8 flex flex-col items-center">
        @if ( count( $targets ) > 1 )
            <h2>I tuoi obiettivi</h2>
        @else
            <h2>Il tuo obiettivo</h2>
        @endif
        @foreach ( $targets as $item )
            <span>
                {{ $item->name }}
                @if ( $item->is_pending )
                    <i class="ib-disabled fa-solid fa-hourglass"></i>
                @else
                    <x-items.modal_icon
                        icon="ib fa-solid fa-spoon"
                        url="{{ route('pending.create', [ 'userId' => $item->id ] ) }}"
                        event="aver ucciso {{ $item->name }}">
                    </x-items.modal_icon>
                @endif
            </span>
        @endforeach
    </div>

@elseif ( $targets[0] instanceof App\Models\Team ) 

    @foreach ($targets as $team)
        <div class="card mx-8 flex flex-col items-center">
            <h2>Squadra avversaria</h2>
            @forelse ( $team->usersAlive() as $u )
                <div>
                    @if ( ( $u->is_team_boss ) && ( App\Http\Controllers\Settings::obtain( 'show_rival_boss' ) == 1 ) )
                        <i class="fa-solid fa-hat-wizard"></i>
                    @endif
                    {{ $u->name }}
                    @if ( $u->is_pending )
                        <i class="ib-disabled fa-solid fa-hourglass"></i>
                    @else
                        <x-items.modal_icon
                            icon="ib fa-solid fa-spoon"
                            url="{{ route('pending.create', [ 'userId' => $u->id ] ) }}"
                            event="aver ucciso {{ $u->name }}">
                        </x-items.modal_icon>
                    @endif
                </div>
            @empty
                <i>Nessun giocatore nella squadra avversaria.</i>
            @endforelse
        </div>
    @endforeach

@endif