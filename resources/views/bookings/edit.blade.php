<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Update Booking') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-sm">
                <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                        @csrf
                        @method('PUT')
        
                        <!-- Booking Date -->
                        <div class="mt-4">
                            <x-input-label for="bookingDate" :value="__('Booking Date')" />
                            <x-text-input id="bookingDate" class="block mt-1 w-full" type="date" name="bookingDate" :value="$booking->bookingDate ?? old('bookingDate')" required autofocus autocomplete="bookingDate" />
                            <x-input-error :messages="$errors->get('bookingDate')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="roomId" :value="__('Room')" />
                            <select id="roomId" name="roomId" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Choose a room</option>
                                @foreach ($rooms as $room )
                                <option value="{{ $room->id }}" {{ $booking->roomId == $room->id ? 'selected' : ''}}>{{ $room->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('roomId')" class="mt-2" />
                        </div>
                
                        <div class="flex items-center justify-end mt-4">
                            <x-danger-link-button class="ms-4" :href="route('bookings.index')">
                                {{ __('Back') }}
                            </x-danger-link-button>
                            <x-primary-button class="ms-4">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
