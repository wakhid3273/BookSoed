<x-crm-layout>
    <x-slot name="title">Daftar | BookSoed</x-slot>

    <div style="display: flex; justify-content: center; align-items: center; min-height: 80vh; padding: 2rem 0;">
        <div class="lg-card fade-up" style="width: 100%; max-width: 480px; padding: 2.5rem 2rem;">
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 class="nouveau-heading" style="font-size: 2.2rem; margin-bottom: 0.5rem;">Daftar Akun</h1>
                <p class="text-muted" style="font-size: 0.85rem;">Bergabung dengan komunitas jual beli buku Unsoed.</p>
            </div>

            @if($errors->any())
                <div class="crm-alert crm-alert-error" style="margin-bottom: 1.5rem;">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style="flex-shrink:0;">
                        <circle cx="8" cy="8" r="7" stroke="#c98ab8" stroke-width="1.2"/>
                        <path d="M5 5l6 6M11 5l-6 6" stroke="#c98ab8" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <div style="font-size: 0.8rem;">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div style="margin-bottom: 1.25rem;">
                    <label for="name" style="display:block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="lg-input" required autofocus placeholder="Jenderal Soedirman">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="email" style="display:block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        Email Unsoed
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="lg-input" required placeholder="nama@mhs.unsoed.ac.id">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="nim_nip" style="display:block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        NIM / NIP
                    </label>
                    <input type="text" id="nim_nip" name="nim_nip" value="{{ old('nim_nip') }}" class="lg-input" required placeholder="H1A021001">
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <label for="password" style="display:block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        Kata Sandi
                    </label>
                    <input type="password" id="password" name="password" class="lg-input" required placeholder="Minimal 8 karakter">
                </div>

                <div style="margin-bottom: 2rem;">
                    <label for="password_confirmation" style="display:block; font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.05em; text-transform: uppercase;">
                        Konfirmasi Kata Sandi
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="lg-input" required placeholder="Ulangi kata sandi">
                </div>

                <button type="submit" class="lg-btn lg-btn-primary" style="width: 100%; justify-content: center; padding: 0.75rem; font-size: 0.9rem;">
                    Daftar
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; font-size: 0.8rem; color: var(--text-muted);">
                Sudah punya akun? 
                <a href="{{ route('login') }}" style="color: var(--petal); text-decoration: none; border-bottom: 1px solid rgba(246,216,58,0.3);">
                    Masuk di sini
                </a>
            </div>
        </div>
    </div>
</x-crm-layout>
