<?php
    $entries = App\Models\RollOfHonorEntry::where('draft',False)->latest()->get();
?>

<x-layouts.main>
    <a class="text-center button" href="{{ route( 'home' ) }}">
        Home
    </a>
    <div class="w-full md:w-3/5">
        @foreach ( $entries as $item )
            <x-layouts.albo-collapse :item="$item" />
        @endforeach
        
        Ringraziamo Luca Barzan, Luca Mattiello e Nicolò Berdin per l'impegno impiegato nel completare al meglio questo albo. <br/>
        Nel caso qualcuno fosse in possesso di ulteriori informazioni, non esiti a spedirmele via mail all'indirizzo 
        <a href="mailto:mors.vobiscum@gmail.com">mors.vobiscum@gmail.com</a>.
</x-layouts.main>