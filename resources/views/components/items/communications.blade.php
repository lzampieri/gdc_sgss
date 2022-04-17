@php
    $comm = App\Http\Controllers\Settings::obtain('communication');
@endphp

@if ( $comm )
    <div class="comm">
        <h2>Comunicazione</h2>
        {!! nl2br($comm) !!}
    </div>
@endif

@auth
    @php
        $comm_priv = App\Http\Controllers\Settings::obtain('communication_private');
    @endphp

    @if ( $comm_priv )
        <div class="comm">
            <h2>Comunicazione</h2>
            {!! nl2br($comm_priv) !!}
        </div>
    @endif
@endauth