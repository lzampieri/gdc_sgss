<x-layouts.main>
    <a class="button" href="{{ route('admin.main') }}"><- Indietro</a>
    @foreach ( App\Http\Controllers\Settings::editable as $s )
        <x-items.update_setup
            :opt_name="$s['name']"
            :opt_title="$s['title']"
            :opt_dict="json_encode( $s['dict'] )">
        </x-items.update_setup>
    @endforeach
</x-layouts.main>