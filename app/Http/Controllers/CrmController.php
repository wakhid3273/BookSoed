<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CrmController — dikelola tim CRM.
 * Menangani: Dashboard Profil Civitas, Wishlist, dan Rating/Review Transaksi.
 */
class CrmController extends Controller
{
    /**
     * Halaman dashboard CRM: ringkasan profil user, statistik, dan riwayat transaksi.
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        $purchaseHistory = Order::with(['orderItems.book', 'delivery', 'review'])
            ->where('buyer_id', $user->id)
            ->latest('order_date')
            ->take(5)
            ->get();

        $salesHistory = Order::with(['orderItems.book', 'buyer'])
            ->where('seller_id', $user->id)
            ->latest('order_date')
            ->take(5)
            ->get();

        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        $purchaseCount = Order::where('buyer_id', $user->id)->where('order_status', 'completed')->count();
        $salesCount = Order::where('seller_id', $user->id)->where('order_status', 'completed')->count();

        return view('crm.dashboard', compact(
            'user',
            'purchaseHistory',
            'salesHistory',
            'wishlistCount',
            'purchaseCount',
            'salesCount',
        ));
    }

    /**
     * Menampilkan daftar buku yang ada di wishlist user.
     */
    public function wishlist(): View
    {
        $wishlists = Wishlist::with(['book.category', 'book.seller'])
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->paginate(12);

        return view('crm.wishlist', compact('wishlists'));
    }

    /**
     * Toggle wishlist: tambah jika belum ada, hapus jika sudah ada.
     */
    public function toggleWishlist(Book $book): RedirectResponse
    {
        $userId = Auth::id();

        $existing = Wishlist::where('user_id', $userId)
            ->where('book_id', $book->book_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Buku dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'book_id' => $book->book_id,
            ]);
            $message = 'Buku ditambahkan ke wishlist!';
        }

        return back()->with('wishlist_status', $message);
    }

    /**
     * Menampilkan form review untuk order yang sudah completed.
     */
    public function showReviewForm(Order $order): View
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        abort_if($order->order_status !== 'completed', 403, 'Review hanya bisa diberikan setelah order selesai.');
        abort_if($order->review !== null, 403, 'Order ini sudah pernah diulas.');

        $order->load(['orderItems.book', 'seller']);

        return view('crm.review', compact('order'));
    }

    /**
     * Menyimpan review dari buyer setelah order selesai.
     *
     * Business Rule:
     * - Hanya buyer dari order tersebut yang boleh membuat review.
     * - Order harus berstatus 'completed'.
     * - Satu order hanya dapat diulas satu kali.
     */
    public function storeReview(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->buyer_id !== Auth::id(), 403);
        abort_if($order->order_status !== 'completed', 403);
        abort_if($order->review !== null, 403);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'order_id' => $order->order_id,
            'buyer_id' => $order->buyer_id,
            'seller_id' => $order->seller_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('crm.dashboard')->with('crm_status', 'Ulasan berhasil dikirim!');
    }
}
