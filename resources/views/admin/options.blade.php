<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}">&lt- Indietro</a>
    @foreach ( App\Http\Controllers\Settings::editable as $s )
        <x-items.update_setup
            :opt_name="$s['name']"
            :opt_title="$s['title']"
            :opt_dict="json_encode( $s['dict'] )">
        </x-items.update_setup>
    @endforeach
    <form class="card flex flex-col items-center w-full md:w-2/5" action="" method="POST" id="theform">
        @csrf
        <h2>Comunicazione</h2>
        <textarea class="w-full" rows="10" name="communication" id="communication">{{ App\Http\Controllers\Settings::obtain('communication') }}</textarea>
        <div class="flex flex-row">
            <a class="button" onClick="cleanAndSend()">Rimuovi</a>
            <a class="button" onClick="trimAndSend()">Salva</a>
        </div>
    </form>
    <form class="card flex flex-col items-center w-full md:w-2/5" action="" method="POST" id="theform_priv">
        @csrf
        <h2>Comunicazione privata</h2>
        <small>Visibile solo agli utenti autenticati</small>
        <textarea class="w-full" rows="10" name="communication_priv" id="communication_priv">{{ App\Http\Controllers\Settings::obtain('communication_private') }}</textarea>
        <div class="flex flex-row">
            <a class="button" onClick="cleanAndSendPriv()">Rimuovi</a>
            <a class="button" onClick="trimAndSendPriv()">Salva</a>
        </div>
    </form>

    <script type="text/javascript">
        function send() {
            $('#theform').submit();
        }

        function cleanAndSend() {
            $('#communication')[0].value = '';
            send();
        }

        function trimAndSend() {
            $('#communication')[0].value = $('#communication')[0].value.trim();
            send();
        }

        function sendPriv() {
            $('#theform_priv').submit();
        }

        function cleanAndSendPriv() {
            $('#communication_priv')[0].value = '';
            sendPriv();
        }

        function trimAndSendPriv() {
            $('#communication_priv')[0].value = $('#communication_priv')[0].value.trim();
            sendPriv();
        }
    </script>

</x-layouts.main>