@php
$boss_editable = App\Http\Controllers\Settings::obtain( 'edit_team_boss' ) == 1
@endphp

@if ( App\Http\Controllers\Settings::obtain( 'team_visible' ) == 1 )
    <div class="card mx-8 flex flex-col items-center">
        <h2>La tua squadra</h2>
        @forelse ( App\Http\Controllers\Teams::myTeam() as $p )
            @if ( $p->is_alive )
                <div>
                    @if ( $p->is_team_boss )
                        <i class="fa-solid fa-hat-wizard"></i>
                    @endif
                    {{ $p->name }}
                    @if( !( $p->is_team_boss ) && $boss_editable )
                        <a class='ib fa-solid fa-hat-wizard tooltiper opacity-40 hover:opacity-100' href="{{ route('edit_team_boss', [ 'userId' => $p->id ] ) }}"><div class="tooltip">Nomina caposquadra</div></a>
                    @endif
                </div>
            @endif
        @empty
            <i>Non risulti in nessuna squadra.</i>
        @endforelse
    </div>
@endif
