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
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-rose-50/60 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <nav class="bg-white/90 backdrop-blur border-b border-rose-100 sticky top-0 z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <svg class="h-8 w-8 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path stroke-linecap="round" d="M12 3c1.5 2.5 1.5 5.5 0 8M12 21c-1.5-2.5-1.5-5.5 0-8M3 12c2.5-1.5 5.5-1.5 8 0M21 12c-2.5 1.5-5.5 1.5-8 0"/>
                                </svg>
                                <span class="font-semibold text-lg text-rose-600">Bloom &amp; Petal</span>
                            </a>

                            <a href="{{ route('home') }}" class="ms-6 text-sm font-medium text-gray-600 hover:text-rose-600">Shop</a>

                            @auth
                                @if ($user->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="ms-4 text-sm font-medium text-rose-600 hover:text-rose-500">Admin</a>
                                @elseif ($user->isCourier())
                                    <a href="{{ route('courier.deliveries.index') }}" class="ms-4 text-sm font-medium text-rose-600 hover:text-rose-500">Deliveries</a>
                                @else
                                    <a href="{{ route('orders.index') }}" class="ms-4 text-sm font-medium text-gray-600 hover:text-rose-600">My Orders</a>
                                @endif
                            @endauth
                        </div>

                        <div class="flex items-center gap-3">
                            @if (! $user?->isAdmin() && ! $user?->isCourier())
                                <a href="{{ route('cart.index') }}" class="relative flex items-center gap-1 text-sm font-medium text-gray-600 hover:text-rose-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                    </svg>
                                    Cart
                                    @if ($cartCount > 0)
                                        <span class="bg-rose-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 text-center min-w-[1.25rem]">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            @endif

                            @auth
                                @if ($user->isAdmin() || $user->isCourier())
                                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-700 hover:text-rose-600">{{ $user->name }}</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-rose-600">{{ $user->name }}</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium">
                                        Log Out
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-rose-600">Log in</a>
                                <a href="{{ route('register') }}" class="text-sm bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-lg font-medium">
                                    Sign up
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            @if (session('success'))
                <div class="bg-emerald-100 border-b border-emerald-200 text-emerald-800 text-sm px-4 py-3 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-b border-red-200 text-red-800 text-sm px-4 py-3 text-center">
                    {{ session('error') }}
                </div>
            @endif

            <main class="flex-1">
                {{ $slot }}
            </main>

            <footer class="bg-white border-t border-rose-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-gray-500">
                    Fresh flowers, delivered with care. &copy; {{ date('Y') }} Bloom &amp; Petal
                </div>
            </footer>
        </div>
    </body>
</html>