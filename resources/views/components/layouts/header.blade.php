<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>GdC SGSS</title>
    
    <base href="{{ env('APP_URL') }}/"> 

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono&display=swap" rel="stylesheet">

    <!-- Style -->
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('css/fa.css')) }}" rel="stylesheet">

    {{-- Javascript --}}
    <script type="text/javascript" src="{{ asset(mix('js/app.js')) }}"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

</head>