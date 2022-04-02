@php
    $method = App\Http\Controllers\Settings::obtain( 'method' );
@endphp

@if ( $method == 'disabled' )
    <div class="card w-2/5">
        Non appena ti saranno assegnati degli obiettivi, compariranno qui. <br />
        Per il momento, non ti rimane altro che aspettare. <br />
        Aspetta, e prega.
    </div>
@else
    <div class="card w-2/5">
        @php $targets = App\Http\Controllers\Targets::targets(); @endphp
        @if ( count( $targets ) > 1 )
            I tuoi obiettivi sono:
        @else
            Il tuo obiettivo è:
        @endif
        @foreach ( $targets as $item )
            <h3>{{ $item->name }}</h3>
        @endforeach
    </div>
@endif

{{-- "__info" => "-2: Tregua<br/>-1: Manutenzione<br/>0: Iscrizioni<br/>1: Squadre<br/>2: Due cicli<br/>3: Sfida finale<br/>4: Premiazioni<br/>5: Preparazione prossima edizione<br/>6: Ciclo unico con obiettivo seguente + successivo<br/>7: Ciclo unico con obiettivo seguente"), --}}