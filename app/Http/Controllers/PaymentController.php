<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Show the payment creation form for an order.
     */
    public function create(Order $order): View|RedirectResponse
    {
        $this->authorize('create', [Payment::class, $order]);

        return view('payments.create', [
            'order' => $order->load(['items.book', 'seller']),
            'providers' => Payment::PROVIDERS,
        ]);
    }

    /**
     * Store a payment for an order.
     */
    public function store(StorePaymentRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $payment = DB::transaction(function () use ($validated, $order) {
            // Check if there's an existing FAILED payment for this order to update
            $existingPayment = $order->payment;

            if ($existingPayment && $existingPayment->isFailed()) {
                $existingPayment->update([
                    'payment_method' => $validated['payment_method'],
                    'ewallet_provider' => $validated['payment_method'] === Payment::METHOD_E_WALLET ? $validated['ewallet_provider'] : null,
                    'amount' => $order->total_amount,
                    'payment_status' => Payment::STATUS_PENDING,
                    'transaction_reference' => null,
                    'paid_at' => null,
                ]);

                return $existingPayment;
            }

            return Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'ewallet_provider' => $validated['payment_method'] === Payment::METHOD_E_WALLET ? $validated['ewallet_provider'] : null,
                'amount' => $order->total_amount,
                'payment_status' => Payment::STATUS_PENDING,
            ]);
        });

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    /**
     * Display the payment details.
     */
    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        return view('payments.show', [
            'payment' => $payment->load(['order.seller', 'order.items.book']),
        ]);
    }

    /**
     * Simulate successful payment (E-Wallet).
     */
    public function simulateSuccess(Payment $payment): RedirectResponse
    {
        $this->authorize('simulate', $payment);

        DB::transaction(function () use ($payment) {
            $ref = 'BOOKSOED-' . now()->timestamp . '-' . strtoupper(substr(md5(uniqid()), 0, 6));

            $payment->update([
                'payment_status' => Payment::STATUS_PAID,
                'transaction_reference' => $ref,
                'paid_at' => now(),
            ]);

            // Update order status to PROCESSING
            $payment->order->update([
                'status' => Order::STATUS_PROCESSING,
            ]);
        });

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dikonfirmasi! Order Anda sedang diproses.');
    }

    /**
     * Simulate failed payment (E-Wallet).
     */
    public function simulateFail(Payment $payment): RedirectResponse
    {
        $this->authorize('simulate', $payment);

        $payment->update([
            'payment_status' => Payment::STATUS_FAILED,
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('error', 'Pembayaran gagal. Anda dapat mencoba kembali.');
    }
}
