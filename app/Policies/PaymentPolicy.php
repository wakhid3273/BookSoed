<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine whether the user can view the payment.
     * Buyer or seller of the order can view the payment, or admin.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->id === $payment->order->buyer_id
            || $user->id === $payment->order->seller_id
            || $user->isAdmin();
    }

    /**
     * Determine whether the user can create a payment for the order.
     * Only the buyer of the order can create payment.
     * Order must not already have an active (PENDING or PAID) payment.
     */
    public function create(User $user, Order $order): bool
    {
        if ($user->id !== $order->buyer_id) {
            return false;
        }

        if ($order->status === Order::STATUS_CANCELLED) {
            return false;
        }

        // If payment exists and is PENDING or PAID, cannot create a new payment
        if ($order->payment && in_array($order->payment->payment_status, [Payment::STATUS_PENDING, Payment::STATUS_PAID])) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can simulate payment.
     * Only the buyer, and payment must be PENDING with E-WALLET method.
     */
    public function simulate(User $user, Payment $payment): bool
    {
        return $user->id === $payment->order->buyer_id
            && $payment->payment_status === Payment::STATUS_PENDING
            && $payment->payment_method === Payment::METHOD_E_WALLET;
    }
}
