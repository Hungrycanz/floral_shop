<x-guest-layout>

    <div class="mb-6 text-center">
        <h1 class="font-normal text-[#3d2020] text-2xl" style="font-family: 'Playfair Display', serif;">Create an account</h1>
        <p class="text-sm text-[#3d2020]/50 mt-1">Join and start ordering beautiful arrangements</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Phone</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required autocomplete="tel"
                   placeholder="e.g. +256 700 000 000"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs uppercase tracking-widest text-[#3d2020]/50 font-medium mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="block w-full rounded-lg border border-[#e8a0a0]/60 bg-[#fdf8f4] focus:border-[#c0565a] focus:ring-[#c0565a] text-sm text-[#3d2020]">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="w-full bg-[#c0565a] hover:bg-[#a84b4f] text-white py-3 rounded-full font-medium text-sm transition-colors mt-2">
            Create account
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-[#3d2020]/50">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-[#c0565a] hover:text-[#a84b4f] transition-colors">
            Log in
        </a>
    </div>
</x-guest-layout>
