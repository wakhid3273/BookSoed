<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Choose Fulfillment Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('deliveries.store', $order) }}" class="space-y-6">
                        @csrf

                        <!-- Delivery Method -->
                        <div>
                            <span class="block text-sm font-medium text-gray-700 dark:text-gray-300">Method</span>
                            <div class="mt-2 space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                                <div class="flex items-center">
                                    <input id="cod" name="delivery_method" type="radio" value="cod" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" required>
                                    <label for="cod" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Cash on Delivery (COD) - Free
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="jeksoed" name="delivery_method" type="radio" value="jeksoed" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" required>
                                    <label for="jeksoed" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Jeksoed - Rp 5.000
                                    </label>
                                </div>
                            </div>
                            @error('delivery_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pickup Location -->
                        <div>
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pickup Location</label>
                            <input type="text" name="pickup_location" id="pickup_location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600" required>
                            @error('pickup_location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Destination -->
                        <div>
                            <label for="destination" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Destination</label>
                            <input type="text" name="destination" id="destination" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600" required>
                            @error('destination')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Confirm Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
