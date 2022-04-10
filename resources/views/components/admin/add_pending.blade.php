@php
    $players = App\Models\User::all();
@endphp

<div class="card flex flex-col items-center">
    <h2>Aggiungi evento presunto</h2>
    <i>(con spedizione mail di conferma)</i>
    <form class="flex flex-row items-center" method="POST" action="{{ route( 'admin.add.pending' ); }}">
        @csrf
        Agente:
        <select name="actor" id="actor">
            @foreach ( $players as $p )
                <option value="{{ $p->id }}">
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
        Vittima:
        <select name="target" id="target">
            @foreach ( $players as $p )
                <option value="{{ $p->id }}">
                    {{ $p->name }}
                </option>
            @endforeach
        </select>
        <input type="submit" value="Aggiungi" class="button" />
    </form>
</div>