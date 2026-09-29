<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h1 class="font-normal text-[#3d2020] text-2xl" style="font-family: 'Playfair Display', serif;">Welcome back</h1>
        <p class="text-sm text-[#3d2020]/50 mt-1">Sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-[#e8a0a0] text-[#c0565a] focus:ring-[#c0565a]">
                <span class="text-sm text-[#3d2020]/60">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-[#c0565a] hover:text-[#a84b4f] transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full bg-[#c0565a] hover:bg-[#a84b4f] text-white py-3 rounded-full font-medium text-sm transition-colors mt-2">
            Log in
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-[#3d2020]/50">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors">
            Register
        </a>
    </div>
</x-guest-layout>
