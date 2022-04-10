@php
    $pendings = Auth::user()->pendings_suffered;
@endphp

@foreach ( $pendings as $p )
    <div class="card mx-8 flex flex-col items-center">
        L'arrogante {{ $p->theactor->name }} afferma di averti ucciso.<br/>
        Confermi questa vergognosa sconfitta?
        <a class="button" href="{{ route('pending.approve', [ 'claimId' => $p->id ] ); }}">
            <i class="fa-solid fa-skull"></i>
            Ahimè, sì, sono morto.
        </a>
        <a class="button" href="{{ route('pending.reject', [ 'claimId' => $p->id ] ); }}">
            <i class="fa-solid fa-heartbeat"></i>
            Giammai, sono ancora vivo.
        </a>
    </div>
@endforeach