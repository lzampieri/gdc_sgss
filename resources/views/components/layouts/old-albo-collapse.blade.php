{{-- @props(['title']) --}}

<div class="card flex flex-col gap-3 items-center" x-data="{ open: false }">
    <h2>{{ $title }}</h2>
    <small>Vincitore:</small>
    <h3>{{ $winner }}</h3>
    <a class="button" @click="open = !open">
        <i :class="open ? 'fa-solid fa-angle-up' : 'fa-solid fa-angle-down'"></i>
    </a>
    
    <div class="overflow-hidden" :class="{'h-0': !open }">
        {{ $slot }}
    </div>
</div>