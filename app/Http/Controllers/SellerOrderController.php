<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SellerOrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Daftar Order masuk untuk seller yang sedang login.
     */
    public function index(): View
    {
        $orders = Order::with(['buyer', 'items.book'])
            ->where('seller_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.seller-index', compact('orders'));
    }

    /**
     * Detail Order dari perspektif seller.
     */
    public function show(Order $order): View
    {
        $this->authorize('viewAsSeller', $order);

        $order->load(['buyer', 'seller', 'items.book.category']);

        return view('orders.seller-show', compact('order'));
    }

    /**
     * Seller memproses Order: PENDING → PROCESSING.
     */
    public function process(Order $order): RedirectResponse
    {
        $this->authorize('process', $order);

        $order->update(['status' => 'PROCESSING']);

        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Order sedang diproses.');
    }
}
