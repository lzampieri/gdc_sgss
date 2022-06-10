<?php
    $entries = App\Models\RollOfHonorEntry::all();
?>

<x-layouts.main x-data="{ selected: {{ session()->get( 'roh_id', -1 ) }} }">
    <script type="text/javascript" src="{{ asset(mix('js/editorjs.js')) }}"></script>
    <script type="text/javascript">
        var visible_editors = {};
        var hidden_editors = {};
    </script>

    @if($errors->any())
        @foreach ($errors->all() as $error)
            <div class="bad-news">{{ $error }}</div>
        @endforeach
    @endif

    <a class="button" href="{{ route('admin.main') }}">&lt- Indietro</a>

    {{-- Card selection --}}
    <div class="flex flex-row max-w-[80%] flex-wrap">
        @foreach ( $entries as $entry )
            <a
                :class="selected == {{ $entry->id }} ? 'selected_button' : 'button'"
                @click="selected = {{ $entry->id }}"
                >
                    @if ( $entry->draft > 0 )
                        <i class="fa-brands fa-firstdraft"></i>
                    @endif
                    {{ empty( $entry->title ) ? "Senza titolo" : $entry->title }}
                </a>
        @endforeach
        <a
            class="button"
            href="{{ route('admin.roll-of-honors.new') }}"
            ><i class="fa-solid fa-file-circle-plus"></i></a>
    </div>

    {{-- Cards --}}
    @foreach ( $entries as $entry )
        <div class="flex flex-col items-center card w-full md:w-3/5" :class="{'hidden': selected != {{ $entry->id }} }">
            <a class="button" onclick="compileAndSubmit( {{ $entry->id }} )">Salva</a>

            <label class="mt-4 text-xs">Titolo</label>
            <input type="text" id="title-{{ $entry->id }}" class="w-full border border-main rounded-3xl mb-4 text-center" value="{{ $entry->title }}"/>
            
            <div class="flex flex-row justify-center w-full" x-data="{'draft_{{ $entry->id }}': {{ $entry->draft ? 1 : 0 }} }">
                <input type="hidden" id="draft-{{ $entry->id }}" :value="draft_{{ $entry->id }}" />
                <a
                    :class="draft_{{ $entry->id }} == 1 ? 'selected_button' : 'button'"
                    @click="draft_{{ $entry->id }} = 1"
                    >Bozza</a>
                <a
                    :class="draft_{{ $entry->id }} == 0 ? 'selected_button' : 'button'"
                    @click="draft_{{ $entry->id }} = 0"
                    >Pubblico</a>
            </div>

            <label class="mt-4 text-xs">Parte visibile</label>
            <div class="border border-main rounded-3xl mb-4 w-full bg-[#fff]" id="visible-{{ $entry->id }}"> </div>
            
            <label class="mt-4 text-xs">Parte nascosta</label>
            <div class="border border-main rounded-3xl mb-4 w-full bg-[#fff]" id="invisible-{{ $entry->id }}"> </div>

            <a class="button" onclick="compileAndSubmit( {{ $entry->id }} )">Salva</a>
        </div>

        <script type="text/javascript">
            visible_editors[ {{$entry->id}} ] = new EditorJS({
                holder: 'visible-{{ $entry->id }}',
                data: JSON.parse( '{!! empty( $entry->visible_area ) ? "{}" : addslashes( $entry->visible_area ) !!}' ),
                tools: Tools
            });
            hidden_editors[ {{$entry->id}} ] = new EditorJS({
                holder: 'invisible-{{ $entry->id }}',
                data: JSON.parse( '{!! empty( $entry->hidden_area ) ? "{}" : addslashes( $entry->hidden_area ) !!}' ),
                tools: Tools
            });
        </script>
    @endforeach

    {{-- Form and submit logic --}}
    <form method="POST" action="" id="the-only-form">
        @csrf
        <input type="hidden" name="id" id="id" />
        <input type="hidden" name="draft" id="draft" />
        <input type="hidden" name="title" id="title" />
        <input type="hidden" name="visible_area" id="visible_area" />
        <input type="hidden" name="hidden_area" id="hidden_area" />
    </form>
    
    <script type="text/javascript">
        async function compileAndSubmit( id ) {
            $('#id')[0].value = id;
            $('#draft')[0].value = $('#draft-' + id)[0].value;
            $('#title')[0].value = $('#title-' + id)[0].value;
            $('#visible_area')[0].value = JSON.stringify( await visible_editors[id].save() );
            console.log( $('#visible_area')[0].value );
            $('#hidden_area')[0].value = JSON.stringify( await hidden_editors[id].save() );
            $('#the-only-form').submit();
        }
    </script>
</x-layouts.main>