@php
    $teams = App\Models\Team::all()->filter( function ($i) { return $i->anyalive(); });
    $cycle = json_decode( App\Http\Controllers\Settings::obtain( 'teams_cycle' ) );

    $teams = $teams->sort( function ( $a, $b) use ( $cycle ) {
        return array_search( $a->id, $cycle ) - array_search( $b->id, $cycle );
    })
@endphp

<x-layouts.main>
    <a class="button" href="{{ route('admin.teams') }}"><- Indietro</a>

    <h2>Ciclo squadre</h2>
    <span>Trascina gli elementi per ordinarli. Il ciclo ha condizioni al contorno periodiche.</span>
    <div class="flex flex-col justify-center" id="teams">
    @foreach ($teams as $t)
        <div class="border-2 border-main rounded-full px-4 py-2" data-id="{{ $t->id }}">
            {{ $t->stringify() }}
        </div>
    @endforeach
    </div>
    <form method="POST" action="">
        @csrf
        <input type="hidden" value="{{ json_encode($cycle) }}" name="cycle" id="cycle" />
        <input type="submit" class="button" value="Salva" />
    </form>

    <script type="text/javascript">
        var el = document.getElementById('teams');
        var sortable = Sortable.create(el,
            { onUpdate: (evt) => $('#cycle')[0].value = JSON.stringify( sortable.toArray().map( (i) => parseInt(i) ) ) }
        );
        $('#cycle')[0].value = JSON.stringify( sortable.toArray().map( (i) => parseInt(i) ) );
    </script>

</x-layouts.main>