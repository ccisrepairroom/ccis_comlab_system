<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ??  'CCIS erma' }}</title>

    <!-- Fonts -->
    <!-- <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="{{asset('js/app.js')}}"></script>

</head>

<body class="bg-slate-200 dark:bg-slate-200" >
    @livewire('partials.navbar')
   <main>
   {{ $slot }}
   </main>
    @livewireScripts
    @livewire('partials.navbar')

</body>

</html>