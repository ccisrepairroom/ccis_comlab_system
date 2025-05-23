<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'CCIS ERMA' }}</title>
         <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/ccis.png') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <script src="{{asset('js/app.js')}}"></script>
        <livewire:styles />
        @stack('styles')
   
    </head>
    <body class="bg-slate-200 dark:bg-slate-200 min-h-screen flex flex-col">
   @if (!request()->routeIs(['facility-monitoring-page', 'equipment-monitoring-page', 'supplies-and-materials-monitoring'])) 
        @livewire('partials.navbar')
   @endif

   <main class="flex-grow">
       {{ $slot }}
   </main>

   @if (!request()->routeIs(['facility-monitoring-page', 'equipment-monitoring-page', 'supplies-and-materials-monitoring'])) 
        @livewire('partials.footer')
   @endif

   @livewireScripts
</body>


</html>