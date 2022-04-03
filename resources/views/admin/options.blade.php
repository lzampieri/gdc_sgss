<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
    <x-items.update_setup
        opt_name="signup_enabled"
        opt_title="Iscrizioni"
        :opt_dict="json_encode([ '0' => 'Chiuse', '1' => 'Aperte'])">
    </x-items.update_setup>
    <x-items.update_setup
        opt_name="method"
        opt_title="Metodo di ciclazione"
        :opt_dict="json_encode([ 'disabled' => 'Nessun obiettivo', 'single_single' => 'Singolo ciclo, singolo obiettivo', 'single_double' => 'Singolo ciclo, obiettivo seguente e successivo'])">
    </x-items.update_setup>
</x-layouts.main>