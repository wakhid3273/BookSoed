<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Admin dapat melakukan semua tindakan.
     */
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Buyer hanya dapat melihat Order miliknya sendiri.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id;
    }

    /**
     * Siapapun yang terautentikasi dapat membuat Order
     * (validasi bisnis dilakukan di controller/service).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Order PENDING dapat dibatalkan oleh buyer.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->buyer_id
            && $order->status === 'PENDING';
    }

    /**
     * Seller dapat mengubah Order ke PROCESSING.
     */
    public function process(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id
            && $order->status === 'PENDING';
    }

    /**
     * Seller dapat melihat Order yang masuk ke dirinya.
     */
    public function viewAsSeller(User $user, Order $order): bool
    {
        return $user->id === $order->seller_id;
    }
}
