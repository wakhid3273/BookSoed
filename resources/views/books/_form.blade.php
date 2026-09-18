{{-- Partial: form fields untuk create/edit buku --}}

{{-- Title --}}
<div>
    <label for="title" class="block text-xs font-semibold text-warm-800 mb-1">Judul Buku <span class="text-rose-500">*</span></label>
    <input id="title" name="title" type="text"
           class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
           value="{{ old('title', $book->title ?? '') }}" required placeholder="Contoh: Kalkulus Lanjut Edisi 8" />
    <x-input-error :messages="$errors->get('title')" class="mt-1" />
</div>

{{-- Author --}}
<div>
    <label for="author" class="block text-xs font-semibold text-warm-800 mb-1">Penulis / Pengarang <span class="text-rose-500">*</span></label>
    <input id="author" name="author" type="text"
           class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
           value="{{ old('author', $book->author ?? '') }}" required placeholder="Contoh: Dale Varberg" />
    <x-input-error :messages="$errors->get('author')" class="mt-1" />
</div>

{{-- ISBN --}}
<div>
    <label for="isbn" class="block text-xs font-semibold text-warm-800 mb-1">ISBN <span class="text-warm-400 font-normal">(Opsional)</span></label>
    <input id="isbn" name="isbn" type="text"
           class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
           value="{{ old('isbn', $book->isbn ?? '') }}" placeholder="Contoh: 978-602-8123-45-6" />
    <x-input-error :messages="$errors->get('isbn')" class="mt-1" />
</div>

{{-- Category & Condition (Grid 2 Kolom) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label for="category_id" class="block text-xs font-semibold text-warm-800 mb-1">Kategori Buku <span class="text-rose-500">*</span></label>
        <select id="category_id" name="category_id" required
                class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ old('category_id', $book->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
    </div>

    <div>
        <label for="condition" class="block text-xs font-semibold text-warm-800 mb-1">Kondisi Fisik Buku <span class="text-rose-500">*</span></label>
        <select id="condition" name="condition" required
                class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
            <option value="">-- Pilih Kondisi --</option>
            @foreach ($conditions as $key => $label)
                <option value="{{ $key }}"
                    {{ old('condition', $book->condition ?? '') === $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('condition')" class="mt-1" />
    </div>
</div>

{{-- Price --}}
<div>
    <label for="price" class="block text-xs font-semibold text-warm-800 mb-1">Harga Jual (Rp) <span class="text-rose-500">*</span></label>
    @if (isset($book) && $book->status === 'RESERVED')
        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 mb-2">
            ⚠️ Harga dikunci karena buku sedang dalam proses transaksi (RESERVED).
        </div>
        <input id="price" name="price" type="number"
               class="w-full bg-warm-100 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-500 cursor-not-allowed"
               value="{{ old('price', $book->price ?? '') }}" readonly />
    @else
        <input id="price" name="price" type="number" step="0.01" min="0"
               class="w-full bg-warm-50 border border-warm-200 rounded-xl px-3.5 py-2 text-sm text-warm-900 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
               value="{{ old('price', $book->price ?? '') }}" required placeholder="Contoh: 45000" />
    @endif
    <x-input-error :messages="$errors->get('price')" class="mt-1" />
</div>

{{-- Photo --}}
<div>
    <label for="photo" class="block text-xs font-semibold text-warm-800 mb-1">Foto Sampul / Kondisi Buku <span class="text-warm-400 font-normal">(Opsional)</span></label>
    @if (isset($book) && $book->photo_path)
        <div class="mb-3 p-3 bg-warm-50 border border-warm-200 rounded-xl flex items-center gap-3">
            <img src="{{ Storage::url($book->photo_path) }}" class="h-16 w-16 object-cover rounded-lg border border-warm-200" alt="Foto saat ini">
            <p class="text-xs text-warm-600">Foto saat ini terpasang. Pilih berkas baru di bawah jika ingin mengganti.</p>
        </div>
    @endif
    <input id="photo" name="photo" type="file" accept="image/*"
           class="w-full text-xs text-warm-600 border border-warm-200 rounded-xl bg-warm-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100 transition cursor-pointer">
    <x-input-error :messages="$errors->get('photo')" class="mt-1" />
</div>
