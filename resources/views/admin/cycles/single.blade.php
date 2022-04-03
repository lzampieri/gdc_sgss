@php
    $players = App\Models\User::where( 'isadmin', False )->get()->filter( function ($i) { return $i->isalive; });
    $cycle = json_decode( App\Http\Controllers\Settings::obtain( 'single_cycle' ) );

    $players = $players->sort( function ( $a, $b) use ($cycle ) {
        return array_search( $a->id, $cycle ) - array_search( $b->id, $cycle );
    })
@endphp

<x-layouts.main>
    <a class="button" href="{{ route('admin.option') }}"><- Indietro</a>

    <h2>Ciclo singolo</h2>
    <span>Trascina gli elementi per ordinarli. Il ciclo ha condizioni al contorno periodiche.</span>
    <div class="flex flex-col justify-center" id="players">
    @foreach ($players as $p)
        <div class="border-2 border-main rounded-full px-4 py-2" data-id="{{ $p->id }}">
            {{ $p->name }}
        </div>
    @endforeach
    </div>
    <form method="POST" action="">
        @csrf
        <input type="hidden" value="{{ json_encode($cycle) }}" name="cycle" id="cycle" />
        <input type="submit" class="button" value="Salva" />
    </form>

    <script type="text/javascript">
        var el = document.getElementById('players');
        var sortable = Sortable.create(el,
            { onUpdate: (evt) => $('#cycle')[0].value = JSON.stringify( sortable.toArray().map( (i) => parseInt(i) ) ) }
        );
        $('#cycle')[0].value = JSON.stringify( sortable.toArray().map( (i) => parseInt(i) ) );
    </script>

</x-layouts.main>