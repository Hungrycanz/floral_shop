<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Bloom & Petal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'DM Sans', sans-serif; background-color: #fdf8f4; color: #3d2020; }
            .font-playfair { font-family: 'Playfair Display', serif; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10 relative overflow-hidden" style="background-color: #fdf8f4;">

            {{-- Subtle floral background pattern --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
                <svg class="absolute top-0 right-0 opacity-20 text-[#e8a0a0]" width="400" height="400" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="6" stroke="currentColor" stroke-width="1"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1"
                          d="M100 60c3 8 3 16 0 24M100 140c-3-8-3-16 0-24M60 100c8-3 16-3 24 0M140 100c-8 3-16 3-24 0"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="0.8"
                          d="M73 73c5-1 10 2 13 6M127 127c-5 1-10-2-13-6M73 127c-1-5 2-10 6-13M127 73c1 5-2 10-6 13"/>
                </svg>
                <svg class="absolute bottom-0 left-0 opacity-15 text-[#f9d5d5]" width="500" height="500" viewBox="0 0 200 200" fill="none">
                    <circle cx="100" cy="100" r="8" stroke="currentColor" stroke-width="1.2"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.2"
                          d="M100 55c4 10 4 20 0 30M100 145c-4-10-4-20 0-30M55 100c10-4 20-4 30 0M145 100c-10 4-20 4-30 0"/>
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="0.9"
                          d="M68 68c6-1 12 2 16 8M132 132c-6 1-12-2-16-8M68 132c-1-6 2-12 8-16M132 68c1 6-2 12-8 16"/>
                </svg>
            </div>

            {{-- Branding --}}
            <a href="/" class="flex flex-col items-center gap-1 mb-8 group">
                <div class="flex items-center gap-2.5">
                    <svg class="h-8 w-8 text-[#c0565a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                        <circle cx="12" cy="12" r="2.5"/>
                        <path stroke-linecap="round" d="M12 3c1.2 2 1.2 4.5 0 6.5M12 21c-1.2-2-1.2-4.5 0-6.5M3 12c2-1.2 4.5-1.2 6.5 0M21 12c-2 1.2-4.5 1.2-6.5 0"/>
                        <path stroke-linecap="round" d="M6.3 6.3c2-.3 3.9.6 5 2.4M17.7 17.7c-2 .3-3.9-.6-5-2.4M6.3 17.7c-.3-2 .6-3.9 2.4-5M17.7 6.3c.3 2-.6 3.9-2.4 5"/>
                    </svg>
                    <span class="font-playfair text-2xl font-normal text-[#3d2020] group-hover:text-[#c0565a] transition-colors" style="font-family: 'Playfair Display', serif;">
                        Bloom &amp; Petal
                    </span>
                </div>
                <span class="text-[10px] uppercase tracking-widest text-[#c0565a] font-medium">Floral Studio</span>
            </a>

            {{-- Card --}}
            <div class="w-full max-w-md bg-white border border-[#e8a0a0]/40 rounded-2xl shadow-md px-8 py-8 relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
