<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Rankkatsu') }}</title>
        
        <meta name="description" content="Crea, vota i comparteix els millors rànquings a Rankkatsu.">
        <meta property="og:site_name" content="Rankkatsu">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Rankkatsu - La plataforma de rànquings definitiva">
        <meta property="og:description" content="Crea, vota i comparteix els millors rànquings a Rankkatsu.">
        <meta property="og:image" content="{{ asset('images/og-default.jpg') }}"> 

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Rankkatsu">
        <meta name="twitter:description" content="Crea, vota i comparteix els millors rànquings a Rankkatsu.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300 h-full">
        @inertia

        <script>
            // Aplicar el tema correctament en carregar la pàgina
            (function() {
                const storedTheme = localStorage.getItem('theme');
                if (storedTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (storedTheme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            })();
        </script>
    </body>
</html>