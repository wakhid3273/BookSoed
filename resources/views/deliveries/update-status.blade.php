<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Delivery Status') }} - Order #{{ $delivery->order_id }}
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

                    <div class="mb-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Current Status</p>
                        <p class="text-lg font-bold">{{ ucfirst(str_replace('_', ' ', $delivery->delivery_status)) }}</p>
                    </div>

                    @php
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
                    @endphp

                    <form method="POST" action="{{ route('deliveries.update-status', $delivery) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-6">
                            <label for="delivery_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Update to:</label>
                            <select id="delivery_status" name="delivery_status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600">
                                @foreach($allowedStatuses as $status)
                                    <option value="{{ $status }}" {{ $status === $delivery->delivery_status ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Save Status
                            </button>
                            <a href="{{ route('deliveries.show', $delivery->order_id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                Back to Tracking
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
