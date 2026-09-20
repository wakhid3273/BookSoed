<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin ERP Dashboard — statistik real-time dari database.
     */
    public function dashboard(): View
    {
        // --- User stats ---
        $totalUsers  = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalStudents = $totalUsers - $totalAdmins;

        // --- Listing stats ---
        $totalBooks     = Book::count();
        $availableBooks = Book::where('status', 'AVAILABLE')->count();
        $reservedBooks  = Book::where('status', 'RESERVED')->count();
        $soldBooks      = Book::where('status', 'SOLD')->count();

        // --- Order stats ---
        $totalOrders      = Order::count();
        $pendingOrders    = Order::where('status', 'PENDING')->count();
        $processingOrders = Order::where('status', 'PROCESSING')->count();
        $completedOrders  = Order::where('status', 'COMPLETED')->count();
        $cancelledOrders  = Order::where('status', 'CANCELLED')->count();

        // --- Payment stats ---
        $totalPayments   = Payment::count();
        $pendingPayments = Payment::where('payment_status', 'PENDING')->count();
        $paidPayments    = Payment::where('payment_status', 'PAID')->count();
        $failedPayments  = Payment::where('payment_status', 'FAILED')->count();

        // --- Transaction value ---
        $totalTransactionValue = Order::sum('total_amount');
        $totalServiceFee       = Order::sum('service_fee');

        // --- Recent orders (10 terbaru) ---
        $recentOrders = Order::with(['buyer', 'seller', 'payment'])
            ->latest()
            ->limit(10)
            ->get();

        // --- Recent users (5 terbaru) ---
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins', 'totalStudents',
            'totalBooks', 'availableBooks', 'reservedBooks', 'soldBooks',
            'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders', 'cancelledOrders',
            'totalPayments', 'pendingPayments', 'paidPayments', 'failedPayments',
            'totalTransactionValue', 'totalServiceFee',
            'recentOrders', 'recentUsers'
        ));
    }

    /**
     * Daftar semua user — dengan search nama/email.
     */
    public function users(Request $request): View
    {
        $query = User::withCount(['books', 'buyerOrders', 'sellerOrders'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Detail satu user.
     */
    public function showUser(User $user): View
    {
        $user->loadCount(['books', 'buyerOrders', 'sellerOrders']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Daftar semua listing — dengan search title/seller.
     */
    public function books(Request $request): View
    {
        $query = Book::with(['seller', 'category'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhereHas('seller', fn ($sq) => $sq->where('name', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->paginate(20)->withQueryString();

        return view('admin.books.index', compact('books'));
    }

    /**
     * Detail satu listing — read-only.
     */
    public function showBook(Book $book): View
    {
        $book->load(['seller', 'category']);

        return view('admin.books.show', compact('book'));
    }

    /**
     * Daftar semua order — dengan filter status.
     */
    public function orders(Request $request): View
    {
        $query = Order::with(['buyer', 'seller', 'payment'])->withCount('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Detail satu order — read-only, snapshot price dari OrderItem.
     */
    public function showOrder(Order $order): View
    {
        $order->load(['buyer', 'seller', 'items.book.category', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Daftar semua payment — dengan filter status.
     */
    public function payments(Request $request): View
    {
        $query = Payment::with(['order.buyer', 'order.seller'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Detail satu payment — read-only.
     */
    public function showPayment(Payment $payment): View
    {
        $payment->load(['order.buyer', 'order.seller', 'order.items.book']);

        return view('admin.payments.show', compact('payment'));
    }
}
