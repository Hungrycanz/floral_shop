@props(['title' => null])

@php
    $cartCount = collect(session('cart', []))->sum('quantity');
    $user = auth()->user();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'DM Sans', sans-serif; background-color: #fdf8f4; color: #3d2020; }
            .font-playfair { font-family: 'Playfair Display', serif; }
            .nav-link-hover { position: relative; }
            .nav-link-hover::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 1.5px;
                background-color: #c0565a;
                transition: width 0.2s ease;
            }
            .nav-link-hover:hover::after { width: 100%; }
        </style>
    </head>
    <body class="antialiased">
        <div class="min-h-screen flex flex-col">

            {{-- Navigation --}}
            <nav class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-[#e8a0a0]/40 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">

                        {{-- Logo --}}
                        <div class="flex items-center gap-6">
                            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                                <svg class="h-7 w-7 text-[#c0565a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                    <circle cx="12" cy="12" r="2.5"/>
                                    <path stroke-linecap="round" d="M12 3c1.2 2 1.2 4.5 0 6.5M12 21c-1.2-2-1.2-4.5 0-6.5M3 12c2-1.2 4.5-1.2 6.5 0M21 12c-2 1.2-4.5 1.2-6.5 0"/>
                                    <path stroke-linecap="round" d="M6.3 6.3c2-.3 3.9.6 5 2.4M17.7 17.7c-2 .3-3.9-.6-5-2.4M6.3 17.7c-.3-2 .6-3.9 2.4-5M17.7 6.3c.3 2-.6 3.9-2.4 5"/>
                                </svg>
                                <div class="leading-tight">
                                    <span class="font-playfair text-lg font-normal text-[#3d2020] block leading-none">Bloom &amp; Petal</span>
                                    <span class="text-[10px] uppercase tracking-widest text-[#c0565a] font-medium">Floral Studio</span>
                                </div>
                            </a>

                            {{-- Nav Links --}}
                            <div class="hidden sm:flex items-center gap-5">
                                <a href="{{ route('home') }}" class="nav-link-hover text-sm font-medium text-[#3d2020]/70 hover:text-[#3d2020] transition-colors">Shop</a>

                                @auth
                                    @if ($user->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="nav-link-hover text-sm font-medium text-[#c0565a] hover:text-[#3d2020] transition-colors">Admin</a>
                                    @elseif ($user->isCourier())
                                        <a href="{{ route('courier.deliveries.index') }}" class="nav-link-hover text-sm font-medium text-[#c0565a] hover:text-[#3d2020] transition-colors">Deliveries</a>
                                    @else
                                        <a href="{{ route('orders.index') }}" class="nav-link-hover text-sm font-medium text-[#3d2020]/70 hover:text-[#3d2020] transition-colors">My Orders</a>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- Right side --}}
                        <div class="flex items-center gap-3">
                            @if (! $user?->isAdmin() && ! $user?->isCourier())
                                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1.5 text-sm font-medium text-[#3d2020]/70 hover:text-[#3d2020] transition-colors px-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Cart</span>
                                    @if ($cartCount > 0)
                                        <span class="absolute -top-1 -right-1 bg-[#c0565a] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            @endif

                            @auth
                                @if ($user->isAdmin() || $user->isCourier())
                                    <span class="text-sm font-medium text-[#3d2020]/70 hidden sm:inline">{{ $user->name }}</span>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-[#3d2020]/70 hover:text-[#3d2020] transition-colors hidden sm:inline">{{ $user->name }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-[#3d2020] border border-[#e8a0a0] hover:bg-[#fdf0f0] px-4 py-1.5 rounded-full transition-colors">
                                        Log Out
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-[#3d2020]/70 hover:text-[#3d2020] transition-colors hidden sm:inline">Log in</a>
                                <a href="{{ route('register') }}" class="text-sm font-medium bg-[#c0565a] hover:bg-[#a84b4f] text-white px-5 py-1.5 rounded-full transition-colors">
                                    Sign up
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="bg-[#fdf0f0] border-b border-[#e8a0a0]/50 text-[#c0565a] text-sm px-4 py-3 text-center font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-[#fdf0f0] border-b border-[#e8a0a0]/50 text-[#a84b4f] text-sm px-4 py-3 text-center font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer style="background-color: #3d2020; color: #fdf8f4;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        {{-- Brand column --}}
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="h-6 w-6 text-[#e8a0a0]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                    <circle cx="12" cy="12" r="2.5"/>
                                    <path stroke-linecap="round" d="M12 3c1.2 2 1.2 4.5 0 6.5M12 21c-1.2-2-1.2-4.5 0-6.5M3 12c2-1.2 4.5-1.2 6.5 0M21 12c-2 1.2-4.5 1.2-6.5 0"/>
                                </svg>
                                <span class="font-playfair text-lg font-normal text-white">Bloom &amp; Petal</span>
                            </div>
                            <p class="text-sm text-white/60 leading-relaxed">Hand-tied bouquets &amp; elegant arrangements, crafted fresh and delivered with care.</p>
                            <p class="text-xs text-white/40 mt-4">123 Garden Lane, Flower District<br>Kampala, Uganda</p>
                        </div>

                        {{-- Links column --}}
                        <div>
                            <h4 class="text-xs uppercase tracking-widest text-[#e8a0a0] font-medium mb-4">Quick Links</h4>
                            <ul class="space-y-2 text-sm">
                                <li><a href="{{ route('home') }}" class="text-white/60 hover:text-[#e8a0a0] transition-colors">Shop</a></li>
                                @auth
                                    <li><a href="{{ route('orders.index') }}" class="text-white/60 hover:text-[#e8a0a0] transition-colors">My Orders</a></li>
                                    <li><a href="{{ route('cart.index') }}" class="text-white/60 hover:text-[#e8a0a0] transition-colors">Cart</a></li>
                                @else
                                    <li><a href="{{ route('login') }}" class="text-white/60 hover:text-[#e8a0a0] transition-colors">Log in</a></li>
                                    <li><a href="{{ route('register') }}" class="text-white/60 hover:text-[#e8a0a0] transition-colors">Create account</a></li>
                                @endauth
                            </ul>
                        </div>

                        {{-- Follow column --}}
                        <div>
                            <h4 class="text-xs uppercase tracking-widest text-[#e8a0a0] font-medium mb-4">Follow Along</h4>
                            <p class="text-sm text-white/60 mb-3">Fresh petals, daily inspiration.</p>
                            <div class="flex gap-3">
                                <span class="text-white/40 text-sm">@bloomandpetal</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-white/10 mt-10 pt-6 text-center text-xs text-white/30">
                        &copy; {{ date('Y') }} Bloom &amp; Petal Floral Studio. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
