<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="font-serif text-2xl font-bold text-warm-900">Daftar Akun Baru</h2>
        <p class="text-xs text-warm-600 mt-1">Bergabunglah dengan komunitas pembaca buku kampus Unsoed</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-warm-800 mb-1">Nama Lengkap</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- NIM / NIP -->
        <div>
            <label for="nim_nip" class="block text-xs font-semibold text-warm-800 mb-1">NIM / NIP</label>
            <input id="nim_nip" type="text" name="nim_nip" :value="old('nim_nip')" required
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="H1A021001" />
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-warm-800 mb-1">Alamat Email Kampus/Pribadi</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="mahasiswa@unsoed.ac.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-warm-800 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-warm-800 mb-1">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2.5 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                   placeholder="Ulangi password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-brand-700 hover:bg-brand-800 text-white font-semibold text-sm rounded-xl shadow-xs transition duration-150">
                Buat Akun Sekarang →
            </button>
        </div>
    </form>

    <div class="mt-6 pt-6 border-t border-warm-200 text-center text-xs text-warm-600">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-bold text-brand-700 hover:underline">Masuk di Sini</a>
    </div>
</x-guest-layout>
