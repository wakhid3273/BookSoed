<x-app-layout>
    <x-admin-nav />

    <div class="py-8 page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-warm-200 pb-6">
                <div>
                    <h1 class="font-serif text-3xl font-bold text-warm-900">Manajemen Pengguna</h1>
                    <p class="text-sm text-warm-600 mt-1">Daftar seluruh pengguna terdaftar di BookSoed</p>
                </div>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
                <div class="relative flex-1 max-w-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau email..."
                           class="w-full pl-4 pr-10 py-2 text-sm border border-warm-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-2.5 text-warm-400 hover:text-warm-700">✕</a>
                    @endif
                </div>
                <button type="submit" class="btn-sm-primary px-4 py-2">Cari</button>
            </form>

            <div class="bg-white rounded-2xl border border-warm-200 shadow-xs overflow-hidden">
                @if ($users->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-3xl mb-3">👥</div>
                        <p class="text-sm text-warm-600">Tidak ada pengguna ditemukan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-warm-50 border-b border-warm-200 text-xs font-semibold text-warm-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5">Nama</th>
                                    <th class="px-4 py-3.5">Email</th>
                                    <th class="px-4 py-3.5 text-center">Role</th>
                                    <th class="px-4 py-3.5 text-center">Listing</th>
                                    <th class="px-4 py-3.5 text-center">Order (Buyer)</th>
                                    <th class="px-4 py-3.5 text-center">Order (Seller)</th>
                                    <th class="px-4 py-3.5 text-right">Bergabung</th>
                                    <th class="px-6 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-warm-100 text-warm-900">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-warm-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-800 flex items-center justify-center font-bold text-xs shrink-0">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="font-semibold text-warm-900 text-sm">{{ $user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-xs text-warm-600">{{ $user->email }}</td>
                                        <td class="px-4 py-4 text-center">
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border
                                                {{ $user->role === 'admin'
                                                   ? 'bg-amber-100 text-amber-800 border-amber-200'
                                                   : 'bg-warm-100 text-warm-700 border-warm-200' }}">
                                                {{ $user->role === 'admin' ? 'Admin' : 'Mahasiswa' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center text-xs font-mono text-warm-800">{{ $user->books_count }}</td>
                                        <td class="px-4 py-4 text-center text-xs font-mono text-warm-800">{{ $user->buyer_orders_count }}</td>
                                        <td class="px-4 py-4 text-center text-xs font-mono text-warm-800">{{ $user->seller_orders_count }}</td>
                                        <td class="px-4 py-4 text-right text-xs text-warm-500">{{ $user->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="px-3 py-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 border border-brand-200 text-xs font-semibold rounded-lg transition-all duration-150 hover:-translate-y-0.5 inline-block">
                                                Detail →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-warm-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
