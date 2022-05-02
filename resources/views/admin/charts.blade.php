<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}">&lt- Indietro</a>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>


    <div class="card w-full md:w-3/5">
        <canvas id="deaths_chart"></canvas>
    </div>

    <script>
        @php
            $counter = App\Models\User::where( 'isadmin', False )->count();
            $events = App\Models\Event::with( [ 'theactor', 'thetarget'] )->oldest()->get();
        @endphp
        var data = [
            @foreach ( $events as $event )
                @php
                    $counter += ( $event->finalstate ? +1 : -1 );
                @endphp
                { x: '{{ $event->created_at }}', y: {{ $counter}} },
            @endforeach
        ]
        new Chart(
            document.getElementById('deaths_chart'),
            {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Current edition',
                        data: data
                    }],
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            // time: {
                            //     unit: 'day'
                            // }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of alive players'
                            }
                        }
                    }
                }
            }
        )
    </script>
</x-layouts.main>