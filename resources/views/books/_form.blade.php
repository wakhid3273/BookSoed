{{-- Partial: form fields untuk create/edit buku --}}

{{-- Title --}}
<div>
    <x-input-label for="title" :value="__('Judul Buku')" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                  :value="old('title', $book->title ?? '')" required />
    <x-input-error :messages="$errors->get('title')" class="mt-1" />
</div>

{{-- Author --}}
<div>
    <x-input-label for="author" :value="__('Penulis')" />
    <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                  :value="old('author', $book->author ?? '')" required />
    <x-input-error :messages="$errors->get('author')" class="mt-1" />
</div>

{{-- ISBN --}}
<div>
    <x-input-label for="isbn" :value="__('ISBN (Opsional)')" />
    <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full"
                  :value="old('isbn', $book->isbn ?? '')" />
    <x-input-error :messages="$errors->get('isbn')" class="mt-1" />
</div>

{{-- Category --}}
<div>
    <x-input-label for="category_id" :value="__('Kategori')" />
    <select id="category_id" name="category_id" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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

{{-- Condition --}}
<div>
    <x-input-label for="condition" :value="__('Kondisi Buku')" />
    <select id="condition" name="condition" required
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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

{{-- Price --}}
<div>
    <x-input-label for="price" :value="__('Harga (Rp)')" />
    @if (isset($book) && $book->status === 'RESERVED')
        <p class="text-xs text-yellow-600 mb-1">⚠️ Harga dikunci karena buku sedang dalam proses transaksi (RESERVED).</p>
        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full bg-gray-100 cursor-not-allowed"
                      :value="old('price', $book->price ?? '')" readonly />
    @else
        <x-text-input id="price" name="price" type="number" step="0.01" min="0"
                      class="mt-1 block w-full"
                      :value="old('price', $book->price ?? '')" required />
    @endif
    <x-input-error :messages="$errors->get('price')" class="mt-1" />
</div>

{{-- Photo --}}
<div>
    <x-input-label for="photo" :value="__('Foto Buku (Opsional)')" />
    @if (isset($book) && $book->photo_path)
        <div class="mt-1 mb-2">
            <img src="{{ Storage::url($book->photo_path) }}" class="h-24 rounded border object-contain" alt="Foto saat ini">
            <p class="text-xs text-gray-400 mt-1">Upload foto baru untuk mengganti foto di atas.</p>
        </div>
    @endif
    <input id="photo" name="photo" type="file" accept="image/*"
           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    <x-input-error :messages="$errors->get('photo')" class="mt-1" />
</div>
