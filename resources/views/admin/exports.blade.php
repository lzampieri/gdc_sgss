<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
    <div class="grid grid-cols-4">
        @foreach ( App\Exports\ExportsController::exportables as $key => $s )
            <div class="card flex flex-col items-center">
                <h2>{{ $s['name'] }}</h2>
                <div class="flex flex-row">
                    <a href="{{ route('admin.export',['table'=>$key, 'type'=>'csv']) }}"
                        class="ib fa-solid fa-file-csv">
                    </a>
                    <a href="{{ route('admin.export',['table'=>$key, 'type'=>'xlsx']) }}"
                        class="ib fa-solid fa-file-excel">
                    </a>
                    <a href="{{ route('admin.export',['table'=>$key, 'type'=>'pdf']) }}"
                        class="ib fa-solid fa-file-pdf">
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.main>