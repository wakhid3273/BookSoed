<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar Order milik buyer yang sedang login.
     */
    public function index(): View
    {
        $orders = Order::with(['seller', 'items.book'])
            ->where('buyer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Form pembuatan Order dari halaman detail Book.
     * book_id dikirim dari halaman books.show.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Order::class);

        // Jika ada book_id query param, pre-select buku tersebut
        $selectedBook = null;
        if ($request->filled('book_id')) {
            $selectedBook = Book::with('seller', 'category')
                ->where('status', 'AVAILABLE')
                ->findOrFail($request->book_id);
        }

        return view('orders.create', compact('selectedBook'));
    }

    /**
     * Proses pembuatan Order.
     * Dilakukan dalam DB transaction + row locking.
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $this->authorize('create', Order::class);

        $buyerId = auth()->id();
        $bookIds = $request->book_ids;

        try {
            $order = DB::transaction(function () use ($buyerId, $bookIds) {

                // 1. Lock buku yang dipesan agar tidak bisa direservasi serentak
                $books = Book::whereIn('id', $bookIds)
                    ->lockForUpdate()
                    ->get();

                // 2. Pastikan semua book_id ditemukan
                if ($books->count() !== count($bookIds)) {
                    throw new \Exception('Beberapa buku tidak ditemukan.');
                }

                // 3. Validasi masing-masing buku
                foreach ($books as $book) {
                    if ($book->status !== 'AVAILABLE') {
                        throw new \Exception("Buku \"{$book->title}\" sudah tidak tersedia.");
                    }
                    if ($book->user_id === $buyerId) {
                        throw new \Exception("Kamu tidak dapat membeli buku milikmu sendiri: \"{$book->title}\".");
                    }
                }

                // 4. Pastikan semua buku dari seller yang sama
                $sellerIds = $books->pluck('user_id')->unique();
                if ($sellerIds->count() > 1) {
                    throw new \Exception('Semua buku dalam satu Order harus berasal dari seller yang sama.');
                }

                $sellerId = $sellerIds->first();

                // 5. Hitung total
                $subtotal = $books->sum('price');
                $serviceFee = Order::SERVICE_FEE;
                $total = $subtotal + $serviceFee;

                // 6. Buat Order
                $order = Order::create([
                    'buyer_id' => $buyerId,
                    'seller_id' => $sellerId,
                    'order_date' => now(),
                    'status' => 'PENDING',
                    'subtotal' => $subtotal,
                    'service_fee' => $serviceFee,
                    'total_amount' => $total,
                ]);

                // 7. Buat OrderItem (snapshot harga saat transaksi)
                foreach ($books as $book) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $book->id,
                        'price' => $book->price,
                        'quantity' => 1,
                    ]);

                    // 8. Ubah status Book menjadi RESERVED
                    $book->update(['status' => 'RESERVED']);
                }

                return $order;
            });

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order berhasil dibuat! Menunggu konfirmasi seller.');

        } catch (\Exception $e) {
            return back()->withErrors(['order' => $e->getMessage()]);
        }
    }

    /**
     * Detail Order milik buyer.
     */
    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        $order->load(['seller', 'buyer', 'items.book.category', 'payment', 'delivery']);

        return view('orders.show', compact('order'));
    }

    /**
     * Pembatalan Order oleh buyer (hanya PENDING).
     * Book dikembalikan menjadi AVAILABLE dalam transaction.
     */
    public function cancel(Order $order): RedirectResponse
    {
        $this->authorize('cancel', $order);

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Kembalikan Book ke AVAILABLE hanya jika masih RESERVED
                // (proteksi: jangan ubah jika sudah SOLD atau kondisi lain)
                if ($item->book && $item->book->status === 'RESERVED') {
                    $item->book->update(['status' => 'AVAILABLE']);
                }
            }

            $order->update(['status' => 'CANCELLED']);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dibatalkan. Buku telah dikembalikan ke listing.');
    }
}
