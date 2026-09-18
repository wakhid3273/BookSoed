<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Services\DeliveryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    public function __construct(
        private DeliveryService $deliveryService
    ) {}

    public function create(Order $order)
    {
        return view('deliveries.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'delivery_method' => ['required', Rule::in(['cod', 'jeksoed'])],
            'pickup_location' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
        ]);

        $this->deliveryService->createDeliveryRequest($order, $validated['delivery_method'], $validated);

        return redirect()->route('deliveries.show', $order);
    }

    public function show(Order $order)
    {
        $order->load('delivery');

        return view('deliveries.show', compact('order'));
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        $statuses = [
            'pending',
            'processing',
            'assigned',
            'picked_up',
            'on_delivery',
            'delivered',
            'completed',
        ];

        $currentIndex = array_search($delivery->delivery_status, $statuses);
        $allowedStatuses = array_slice($statuses, $currentIndex);

        $validated = $request->validate([
            'delivery_status' => ['required', Rule::in($allowedStatuses)],
        ]);

        $this->deliveryService->updateStatus($delivery, $validated['delivery_status']);

        return back()->with('status', 'Delivery status updated!');
    }
}
