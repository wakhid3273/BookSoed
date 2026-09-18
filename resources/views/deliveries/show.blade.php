<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Delivery Tracking') }} - Order #{{ $order->order_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if(session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium leading-6 mb-4">Method: {{ strtoupper($order->delivery->delivery_method) }}</h3>
                    
                    <div class="mb-6 flex items-center">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Status: {{ ucfirst(str_replace('_', ' ', $order->delivery->delivery_status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="border rounded-md p-4 dark:border-gray-700">
                            <h4 class="font-medium text-gray-500 dark:text-gray-400 mb-2">Location Details</h4>
                            <p><strong>From:</strong> {{ $order->delivery->pickup_location }}</p>
                            <p><strong>To:</strong> {{ $order->delivery->destination }}</p>
                        </div>
                        
                        @if($order->delivery->delivery_method === 'jeksoed')
                        <div class="border rounded-md p-4 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                            <h4 class="font-medium text-gray-500 dark:text-gray-400 mb-2">Jeksoed Driver Info</h4>
                            <p><strong>Order ID:</strong> {{ $order->delivery->jeksoed_order_id }}</p>
                            <p><strong>Name:</strong> {{ $order->delivery->driver_name }}</p>
                            <p><strong>Phone:</strong> {{ $order->delivery->driver_phone }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 border-t dark:border-gray-700 pt-6">
                        <a href="{{ route('deliveries.update-status', $order->delivery) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium">
                            [Seller] Update Status &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
