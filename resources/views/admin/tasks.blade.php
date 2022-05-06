<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}">&lt- Indietro</a>
    
    @php
        $passcodes = App\Models\NightTaskPasscode::all();
    @endphp

    <div class="card">
        <h2>Lavori programmati</h2>
        @forelse ( App\Models\NightTask::oldest()->get() as $item )
            <p>
                {{ $item->thepasscode->title }} | 
                {{ $item->explain() }}
                <a class="ib fa-solid fa-trash tooltiper" href="{{ route('admin.task.delete', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
            </p>
        @empty 
            Nessun lavoro in programma
        @endforelse
    </div>

    <div class="card">
        <form
            class="flex flex-col items-center gap-2"
            method="POST"
            action="{{ route( 'admin.task.add', [ 'type' => 'change_option' ] ); }}"
            x-data="{ option_name: '{{ App\Http\Controllers\Settings::editable[0]['name'] }}' }">
            <h2>Programma lavoro: modifica opzione</h2>
            @csrf
            <select class="w-full" name="passcode" id="passcode">
                @foreach ( $passcodes as $p )
                    <option value="{{ $p->id }}">
                        {{ $p->title }}
                    </option>
                @endforeach
            </select>
            @error( 'passcode' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <select class="w-full" name="option_name" id="option_name" x-model="option_name">
                @foreach ( App\Http\Controllers\Settings::editable as $s )
                    <option value="{{ $s['name'] }}">
                        {{ $s['title'] }}
                    </option>
                @endforeach
            </select>
            @error( 'option_name' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <select class="w-full" name="option_value" id="option_value" >
                @foreach ( App\Http\Controllers\Settings::editable as $s )
                    @foreach ( $s['dict'] as $ok => $ov )
                        <option value="{{ $ok }}" x-show="option_name == '{{ $s['name'] }}'">
                            {{ $ov }}
                        </option>
                    @endforeach
                @endforeach
            </select>
            @error( 'option_value' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <small>Eseguiti una sola volta, poi eliminati</small>
            <input type="submit" value="Programma" class="button" />
        </form>
    </div>

    <div class="card">
        <form
            class="flex flex-col items-center gap-2"
            method="POST"
            action="{{ route( 'admin.task.add', [ 'type' => 'add_event' ] ); }}" >
            <h2>Programma lavoro: evento</h2>
            @csrf
            <select class="w-full" name="passcode" id="passcode">
                @foreach ( $passcodes as $p )
                    <option value="{{ $p->id }}">
                        {{ $p->title }}
                    </option>
                @endforeach
            </select>
            @error( 'passcode' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <select class="w-full" name="finalState" id="finalState">
                <option value="-1">Dichiarazione di morte</option>
                <option value="0" selected>Morte</option>
                <option value="1">Resurrezione</option>
            </select>
            @error( 'finalState' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            Agente:
            <select class="w-full" name="actor" id="actor">
                @foreach ( App\Models\User::all() as $u )
                    <option value="{{ $u['id'] }}">
                        {{ $u['name'] }}
                    </option>
                @endforeach
            </select>
            @error( 'actor' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            Vittima:
            <select class="w-full" name="target" id="target">
                @foreach ( App\Models\User::all() as $u )
                    <option value="{{ $u['id'] }}">
                        {{ $u['name'] }}
                    </option>
                @endforeach
            </select>
            @error( 'target' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <div>
                <input type="checkbox" name="sendmail" id="sendmail" /> Invia mail
            </div>
            <div>
                <input type="checkbox" name="resurrections" id="resurrections" /> Esegui eventuali resurrezioni
            </div>
            <input type="submit" value="Programma" class="button" />
        </form>
    </div>

    <div class="card">
        <form
            class="flex flex-col items-center gap-2"
            method="POST"
            action="{{ route( 'admin.task.add', [ 'type' => 'shuffle_cycle' ] ); }}" >
            <h2>Programma lavoro: mescolamento cicli</h2>
            @csrf
            <select class="w-full" name="passcode" id="passcode">
                @foreach ( $passcodes as $p )
                    <option value="{{ $p->id }}">
                        {{ $p->title }}
                    </option>
                @endforeach
            </select>
            @error( 'passcode' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <select class="w-full" name="cycle_name" id="cycle_name">
                @foreach ( App\Http\Controllers\Settings::cycles as $s )
                    <option value="{{ $s['name'] }}">
                        {{ $s['title'] }}
                    </option>
                @endforeach
            </select>
            @error( 'cycle_name' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <small>Eseguito sempre fino a eliminazione manuale.</small>
            <input type="submit" value="Programma" class="button" />
        </form>
    </div>

    <div class="card">
        <h2>Registrazione nuovi cronjob</h2>
        <span class="text-red">Non utilizzare se non pienamente coscienti</span>
        <form class="flex flex-row items-center gap-2" method="POST" action="{{ route( 'admin.cronjob.add' ); }}">
            @csrf
            Titolo:
            <input name="title" id="title" type="text" />
            Codice:
            <input name="code" id="code" type="text" />
            <input type="submit" value="Salva" class="button" />
            @error( 'title' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            @error( 'code' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
        </form>
        <h2>Cronjob esistenti</h2>
        @forelse ( $passcodes as $item )
            <p>
                {{ $item->title }}
                <a class="ib fa-solid fa-trash tooltiper" href="{{ route('admin.cronjob.delete', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
            </p>
        @empty 
            Nessun cronjob registrato
        @endforelse
    </div>
</x-layouts.main>