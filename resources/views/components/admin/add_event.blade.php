@php
    $players = App\Models\User::all();
@endphp

<div class="card flex flex-col">
    <h2>Aggiungi morte</h2>
    <form class="flex flex-row items-center" method="POST" action="{{ route( 'admin.add.event' ); }}">
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
        <input type="hidden" id="finalState" name="finalState" value="0" />
        <input type="submit" value="Aggiungi" class="button" />
    </form>
</div>

<div class="card flex flex-col">
    <h2>Aggiungi resurrezione</h2>
    <form class="flex flex-row items-center" method="POST" action="{{ route( 'admin.add.event' ); }}">
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
        <input type="hidden" id="finalState" name="finalState" value="1" />
        <input type="submit" value="Aggiungi" class="button" />
    </form>
</div>