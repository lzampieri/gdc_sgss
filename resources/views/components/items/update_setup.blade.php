@props( ['opt_name', 'opt_title', 'opt_dict'])
@php $optvalue = App\Http\Controllers\Settings::obtain( $optName ); @endphp

<div class="card flex flex-col gap-2">
    <h2 >{{ $optTitle }}</h2>
    <div class="flex flex-row flex-wrap gap-2">
        @foreach ( json_decode( $optDict ) as $key => $value )
            <a
                class="{{ $optvalue == $key ? 'selected_button' : 'button' }}"
                href="{{ route('option.update', [ 'key' => $optName, 'value' => $key ] ) }}" >
                {{ $value }}
            </a>
        @endforeach
    </div>
</div>