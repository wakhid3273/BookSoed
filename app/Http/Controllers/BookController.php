<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    use AuthorizesRequests;
    /**
     * Public marketplace: tampilkan buku AVAILABLE (dan RESERVED untuk info).
     */
    public function index(Request $request): View
    {
        $query = Book::with(['seller', 'category'])
            ->whereIn('status', ['AVAILABLE', 'RESERVED'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $books      = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    /**
     * Detail publik satu buku.
     */
    public function show(Book $book): View
    {
        $book->load(['seller', 'category']);

        return view('books.show', compact('book'));
    }

    // ----------------------------------------------------------------
    // Seller area
    // ----------------------------------------------------------------

    /**
     * Daftar listing milik seller yang login.
     */
    public function myListings(Request $request): View
    {
        $books = Book::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('books.my-listings', compact('books'));
    }

    /**
     * Form buat listing baru.
     */
    public function create(): View
    {
        $this->authorize('create', Book::class);

        $categories = Category::orderBy('name')->get();
        $conditions = Book::conditions();

        return view('books.create', compact('categories', 'conditions'));
    }

    /**
     * Simpan listing baru.
     */
    public function store(StoreBookRequest $request): RedirectResponse
    {
        $this->authorize('create', Book::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status']  = 'AVAILABLE';

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('books', 'public');
        }

        $book = Book::create($data);

        return redirect()->route('books.my-listings')
            ->with('success', 'Listing buku berhasil dibuat!');
    }

    /**
     * Form edit listing.
     */
    public function edit(Book $book): View
    {
        $this->authorize('update', $book);

        $categories = Category::orderBy('name')->get();
        $conditions = Book::conditions();

        return view('books.edit', compact('book', 'categories', 'conditions'));
    }

    /**
     * Update listing.
     * Business rule:
     * - Tidak dapat diedit jika SOLD.
     * - Jika RESERVED, hanya field non-kritis (judul, author, isbn) yang diperbolehkan berubah; harga dikunci.
     */
    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $this->authorize('update', $book);

        $data = $request->validated();

        // Jika RESERVED, kunci harga agar tidak berubah (melindungi transaksi aktif)
        if ($book->status === 'RESERVED') {
            $data['price'] = $book->price;
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($book->photo_path) {
                Storage::disk('public')->delete($book->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('books', 'public');
        }

        $book->update($data);

        return redirect()->route('books.my-listings')
            ->with('success', 'Listing buku berhasil diperbarui!');
    }

    /**
     * Hapus listing.
     * Hanya jika status AVAILABLE dan belum pernah masuk ke order_items.
     */
    public function destroy(Book $book): RedirectResponse
    {
        $this->authorize('delete', $book);

        if ($book->photo_path) {
            Storage::disk('public')->delete($book->photo_path);
        }

        $book->delete();

        return redirect()->route('books.my-listings')
            ->with('success', 'Listing buku berhasil dihapus.');
    }
}
