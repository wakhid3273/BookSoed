<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-serif text-2xl font-bold text-warm-900">Masuk ke Akun</h2>
        <p class="text-xs text-warm-600 mt-1">Silakan masuk menggunakan email kampus/terdaftar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-warm-800 mb-1">Alamat Email</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="mahasiswa@unsoed.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-warm-800 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-warm-300 text-brand-700 focus:ring-brand-500">
                <span class="ms-2 text-warm-700">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-brand-700 font-semibold hover:underline" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-xs transition duration-150">
                Masuk Sekarang →
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-warm-200 text-center text-xs text-warm-600">
        Belum memiliki akun BookSoed?
        <a href="{{ route('register') }}" class="font-bold text-brand-700 hover:underline">Daftar Akun Baru</a>
    </div>
</x-guest-layout>
