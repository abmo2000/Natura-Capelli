<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Kurale&display=swap" rel="stylesheet">

          <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            (() => {
                try {
                    const storedTheme = localStorage.getItem('qaid_theme');
                    const theme = storedTheme === 'light' ? 'light' : 'dark';

                    document.documentElement.classList.toggle('theme-light', theme === 'light');
                    document.documentElement.dataset.theme = theme;
                } catch (_) {
                    document.documentElement.classList.remove('theme-light');
                    document.documentElement.dataset.theme = 'dark';
                }
            })();
        </script>

        <!-- Styles / Scripts -->
         @vite(['resources/css/app.css', 'resources/js/app.js'])
           
    
         @stack('styles_bottom')
    </head>
    <body class="app-body">
        @yield('content')
        <x-flash-message></x-flash-message>
        <x-footer></x-footer>
        @stack('scripts_bottom')
    </body>
</html>
