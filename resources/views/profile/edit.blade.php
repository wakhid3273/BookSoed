<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="border-b border-warm-200 pb-4">
                <h1 class="font-serif text-2xl font-bold text-warm-900">Pengaturan Profil</h1>
                <p class="text-xs text-warm-700 mt-1">Kelola data informasi akun, keamanan password, dan preferensi akun Anda</p>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
