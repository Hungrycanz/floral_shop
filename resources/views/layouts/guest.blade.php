<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-rose-50 via-rose-50/60 to-amber-50">
            <div class="mb-4">
                <a href="/" class="flex items-center gap-2">
                    <svg class="h-10 w-10 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3"/>
                        <path stroke-linecap="round" d="M12 3c1.5 2.5 1.5 5.5 0 8M12 21c-1.5-2.5-1.5-5.5 0-8M3 12c2.5-1.5 5.5-1.5 8 0M21 12c-2.5 1.5-5.5 1.5-8 0"/>
                    </svg>
                    <span class="font-semibold text-xl text-rose-600">Bloom &amp; Petal</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-md sm:shadow-lg overflow-hidden sm:rounded-2xl border border-rose-100">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>