<script type="text/javascript" src="{{ asset(mix('js/editorjs.js')) }}"></script>

<div class="card flex flex-col gap-3 items-center" x-data="{ open: false }">
    <h2>{!! $item->title !!}</h2>
    <div class="w-full" id="visible-{{ $item->id }}"></div>
    <a class="button" @click="open = !open">
        <i :class="open ? 'fa-solid fa-angle-up' : 'fa-solid fa-angle-down'"></i>
    </a>
    
    <div class="overflow-hidden w-full" :class="{'h-0': !open }">
        <div class="w-full" id="invisible-{{ $item->id }}"></div>
    </div>

    
    <script type="text/javascript">
        new EditorJS({
            holder: 'visible-{{ $item->id }}',
            data: JSON.parse( '{!! empty( $item->visible_area ) ? "{}" : addslashes( $item->visible_area ) !!}' ),
            tools: Tools,
            readOnly: true,
            minHeight : 0
        });
        new EditorJS({
            holder: 'invisible-{{ $item->id }}',
            data: JSON.parse( '{!! empty( $item->hidden_area ) ? "{}" : addslashes( $item->hidden_area ) !!}' ),
            tools: Tools,
            readOnly: true,
            minHeight : 0
        });
    </script>
</div>