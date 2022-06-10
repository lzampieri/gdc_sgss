<x-layouts.main>

    <div class="flex flex-row flex-wrap items-center justify-center">
        <a class="button" href="{{ route('home') }}">Home</a>
        <a class="button" href="{{ route('admin.option') }}">Opzioni di gioco</a>
        <a class="button" href="{{ route('admin.teams') }}">Squadre</a>
        <a class="button" href="{{ route('admin.cycles.single') }}">Ciclo singoli</a>
        <a class="button" href="{{ route('admin.stats') }}">Statistiche</a>
        <a class="button" href="{{ route('admin.tasks') }}">Compiti</a>
        <a class="button" href="{{ route('admin.roll-of-honors') }}">Albo d'oro</a>
        <a class="button" href="{{ route('admin.exports') }}">Esporta</a>
        <a class="button" href="{{ route('admin.reset') }}">Reset</a>
        <a class="button" href="https://t.me/+3AOObxQWxUc0MTE0">Notifiche</a>
    </div>

    <div class="card">
        <h2>Giocatori</h2>
        <h3> {{ App\Models\User::where( 'isadmin', False )->count() }} giocatori iscritti</h3>
        <h3> {{ App\Models\User::where( 'isadmin', False )->get()->filter( function ($i) { return $i->is_alive; })->count() }} giocatori vivi</h3>
        <table class="table-auto">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Stato</th>
                <th>Data registrazione</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach ( App\Models\User::where( 'isadmin', False )->orderBy('name')->get() as $item )
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    @if ( $item->is_alive )
                        <span class="text-green">Vivo</span>
                        @if ( $item->is_pending )
                            <span class="text-red"> (Morte presunta)</span>
                        @endif
                    @else
                        <span class="text-red">Morto</span>
                    @endif
                </td>
                <td>
                    {{ $item->created_at }}
                </td>
                <td>
                    <a class="ib fa-solid fa-trash tooltiper" href="{{ route('user.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                    <a class="ib fa-solid fa-toolbox tooltiper" href="{{ route('user.admin', [ 'id' => $item->id ] ) }}"><div class="tooltip">Promuovi</div></a>
                </td>
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Amministratori</h2>
        <h3> {{ App\Models\User::where( 'isadmin', True )->count() }} amministratori</h3>
        <table class="table-auto">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Data registrazione</th>
            </tr>
        </thead>
        <tbody>
        @foreach ( App\Models\User::where( 'isadmin', True )->get() as $item )
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    {{ $item->created_at }}
                </td>
                <td>
                    <a class="ib fa-solid fa-trash tooltiper" href="{{ route('user.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                    <a class="ib fa-solid fa-hammer tooltiper" href="{{ route('user.deadmin', [ 'id' => $item->id ] ) }}"><div class="tooltip">Arretra</div></a>
                </td>
            </tr>
        @endforeach
        </tbody>
        </table>
    </div>
        
    <a href="{{ route( 'admin.deleted' ) }}">Giocatori eliminati</a>

    <div class="card">
        <h2>Mailing lists</h2>
        <h3>Tutti i giocatori</h3>
        @foreach ( App\Models\User::where( 'isadmin', False )->get() as $item )
            {{ $item->email }},
        @endforeach
        
        <h3>Giocatori vivi</h3>
        @foreach ( App\Models\User::where( 'isadmin', False )->get() as $item )
            @if ( $item->is_alive )
                {{ $item->email }},
            @endif
        @endforeach
        
        <h3>Amministratori</h3>
        @foreach ( App\Models\User::where( 'isadmin', True )->get() as $item )
            {{ $item->email }},
        @endforeach
    </div>

    <div class="card">
        <h2>Eventi</h2>
        <table class="table-auto">
        <thead>
            <tr>
                <th>Data</th>
                <th>Agente</th>
                <th>Subente</th>
                <th>Stato finale del subente</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ( App\Models\Event::with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
            <tr>
                
                <td>{{ $item->created_at }}</td>
                <td>
                    {!! $item->theactor ? $item->theactor->name : "<span class=\"text-red\">Ignoto</span>" !!}
                </td>
                <td>
                    {!! $item->thetarget ? $item->thetarget->name : "<span class=\"text-red\">Ignoto</span>" !!}
                </td>
                <td>
                    @if ( $item->finalstate )
                        <span class="text-green">Vivo</span>
                    @else
                        <span class="text-red">Morto</span>
                    @endif
                </td>
                <td>
                    <a class="ib fa-solid fa-trash tooltiper" href="{{ route('event.trash', [ 'id' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                </td>
            </tr>
        @empty 
            Nessun evento registrato
        @endforelse
        </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Eventi in attesa</h2>
        <table class="table-auto">
        <thead>
            <tr>
                <th>Data</th>
                <th>Agente</th>
                <th>Subente</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ( App\Models\PendingKill::with( [ 'theactor', 'thetarget'] )->latest()->get() as $item )
            <tr>
                
                <td>{{ $item->created_at }}</td>
                <td>
                    {!! $item->theactor ? $item->theactor->name : "<span class=\"text-red\">Ignoto</span>" !!}
                </td>
                <td>
                    {!! $item->thetarget ? $item->thetarget->name : "<span class=\"text-red\">Ignoto</span>" !!}
                </td>
                <td>
                    <a class="ib fa-solid fa-check tooltiper" href="{{ route('pending.approve', [ 'claimId' => $item->id ] ) }}"><div class="tooltip">Conferma</div></a>
                    <a class="ib fa-solid fa-trash tooltiper" href="{{ route('pending.reject', [ 'claimId' => $item->id ] ) }}"><div class="tooltip">Elimina</div></a>
                </td>
            </tr>
        @empty
            Nessun evento in attesa
        @endforelse
        </tbody>
        </table>
    </div>

    <div class="card">
        <form
            class="flex flex-col items-center gap-2"
            method="POST"
            action="{{ route( 'admin.add.event' ); }}" >
            <h2>Aggiungi evento</h2>
            @csrf
            <select class="w-full" name="finalState" id="finalState">
                <option value="-1">Dichiarazione di morte</option>
                <option value="0" selected>Morte</option>
                <option value="1">Resurrezione</option>
            </select>
            @error( 'finalState' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <div class="flex flex-row items-center">
                Agente:
                <select class="w-full" name="actor" id="actor">
                    @foreach ( App\Models\User::all() as $u )
                        <option value="{{ $u['id'] }}">
                            {{ $u['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error( 'actor' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <div class="flex flex-row items-center">
                Vittima:
                <select class="w-full" name="target" id="target">
                    @foreach ( App\Models\User::all() as $u )
                        <option value="{{ $u['id'] }}">
                            {{ $u['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error( 'target' )
                <span class="text-red"> {{ $message }} </span>
            @enderror
            <div>
                <input type="checkbox" name="sendmail" id="sendmail" /> Invia mail
            </div>
            <div>
                <input type="checkbox" name="resurrections" id="resurrections" /> Esegui eventuali resurrezioni
            </div>
            <input type="submit" value="Salva" class="button" />
        </form>
    </div>

    <a href="{{ route( 'admin.deleted' ) }}">Eventi eliminati</a>
    <a href="{{ route( 'admin.logs' ) }}">Logs</a>
</x-layouts.main>