<?php

namespace App\Services;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Support\Str;

class DeliveryService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function createDeliveryRequest(Order $order, string $method, array $data): Delivery
    {
        $deliveryFee = 0;
        $status = 'pending';
        $jeksoedData = [];

        if ($method === 'jeksoed') {
            $deliveryFee = 5000;
            $status = 'assigned';
            $jeksoedData = [
                'jeksoed_order_id' => 'JKS-'.strtoupper(Str::random(5)),
                'driver_name' => 'Driver '.fake()->firstName(),
                'driver_phone' => fake()->phoneNumber(),
            ];
        }

        $order->update([
            'fulfillment_method' => $method,
            'delivery_fee' => $deliveryFee,
        ]);

        return Delivery::create(array_merge([
            'order_id' => $order->order_id,
            'delivery_method' => $method,
            'delivery_status' => $status,
            'pickup_location' => $data['pickup_location'],
            'destination' => $data['destination'],
        ], $jeksoedData));
    }

    public function updateStatus(Delivery $delivery, string $newStatus): void
    {
        $delivery->update(['delivery_status' => $newStatus]);

        if ($newStatus === 'completed') {
            $delivery->order->update([
                'order_status' => 'completed',
                'completed_at' => now(),
            ]);

            foreach ($delivery->order->orderItems as $item) {
                $item->book->update(['status' => 'sold']);
            }
        }
    }
}
