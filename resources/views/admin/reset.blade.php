@php
    $users = App\Models\User::where( 'isadmin', True )->get();    
    $pass = App\Http\Controllers\ResetGame::get_reset_key();
@endphp

<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}">&lt- Indietro</a>

    @if($errors->any())
        <div class="bad-news">Argh, qui c'è un errore!</div>
    @endif

    <div class="flex flex-col items-center" x-data="{ 'step': 0 }">
        <h2>Attenzione: area pericolosa!</h2>
        <h4>Da questa pagina è possibile resettare completamente il gioco</h4>
        <div class="card flex flex-col items-center">
            <p>Ti è chiaro che sei in una zona pericolosa?</p>
            <a @click="step = 1" class="button">Sì</a>
        </div>
        <div class="card flex flex-col items-start" x-show=" step > 0 ">
            <p>Confermi di non essere sotto effetto di sostanze psicotrope?</p>
            <p>Confermi di essere nel pieno possesso delle tue capacità mentali?</p>
            <a @click="step = 2" class="button">Sì</a>
        </div>
        <div class="card flex flex-col items-end" x-show=" step > 1 ">
            <h4>Eventi</h4>
            <p>Verranno eliminati tutti gli eventi: morti, resurrezioni, questo e quell'altro. Sarà tutto cancellato.</p>
            <a @click="step = 3" class="button">Ok sono d'accordo</a>
        </div>
        <div class="card flex flex-col items-start" x-show=" step > 2 ">
            <h4>Utenti</h4>
            <p>Verranno eliminati tutti gli utenti: credenziali, attività ed eventi a loro collegati.</p>
            <p>Non sarà più possibile a nessuno fare il login, eccetto agli amministratori.</p>
            <a @click="step = 4" class="button">Ok mi sembra giusto</a>
        </div>
        <div class="card flex flex-col items-center" x-show=" step > 3 ">
            <h4>Amministratori</h4>
            <p>Le uniche persone che riusciranno ad accedere al sito sono:</p>
            @forelse ($users as $user)
                <i>{{ $user->name }}</i>    
            @empty
                <b>NESSUNO!</b>
            @endforelse
            <a @click="step = 5" class="button">Ok è corretto</a>
        </div>
        <div class="card flex flex-col items-start" x-show=" step > 4 ">
            <small>È tuo diritto sapere che nessuno di questi dati è davvero eliminato. Sono accantonati in una parte losca del sito però diventeranno di difficile reperimento, e soprattutto sarà assai difficile ricostruire la struttura.</small>
            <small>Inoltre, verrà fatto un backup prima di eliminare tutto.</small>
            <small>Infine, ti avvertiamo che saranno resi pubblici tutti i dati anonimizzati relativi a morti e resurrezioni avvenute prima di oggi.</small>
            <a @click="step = 6" class="button">Oro benon</a>
        </div>
        <div class="card flex flex-col items-start" x-show=" step > 5 ">
            Ok sei arrivato alla fine. Ci serve solo una ultima conferma.
            <a @click="step = 7" class="button">Confermo!</a>
        </div>
        <div class="card flex flex-col items-center" x-show=" step > 6 ">
            <p>Per verificare che tu sia umano, cosciente e sobrio, ti chiedo di scrivere nel campo qui sotto il codice <b>{{ $pass }}</b>, letto però <u>da destra a sinistra</u>.</p>
            <form method="POST" action="">
                @csrf
                <input type="text" id="the_reset_pass" name="the_reset_pass" class="w-full border border-main rounded-3xl mb-4 text-center" />
                <input type="submit" value="Reset!" class="button" />
            </form>
        </div>
    </div>
</x-layouts.main>