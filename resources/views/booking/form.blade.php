<x-guest-layout>
    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        
        <!-- Customer Name -->
        <div>
            <x-input-label for="customer_name" :value="__('Customer Name')" />
            <x-text-input id="customer_name" class="block mt-1 w-full" type="text" name="customer_name" :value="old('customer_name')" required />
            <x-input-error :messages="$errors->get('customer_name')" class="mt-2" />
        </div>

        <!-- Customer Email -->
        <div class="mt-4">
            <x-input-label for="customer_email" :value="__('Customer Email')" />
            <x-text-input id="customer_email" class="block mt-1 w-full" type="email" name="customer_email" :value="old('customer_email')" required />
            <x-input-error :messages="$errors->get('customer_email')" class="mt-2" />
        </div>

        <!-- Booking Date -->
        <div class="mt-4">
            <x-input-label for="booking_date" :value="__('Booking Date')" />
            <x-text-input id="booking_date" class="block mt-1 w-full" type="date" name="booking_date" :value="old('booking_date')" required />
            <x-input-error :messages="$errors->get('booking_date')" class="mt-2" />
        </div>

        <!-- Booking Type -->
        <div class="mt-4">
            <x-input-label for="booking_type" :value="__('Booking Type')" />
            <x-select id="booking_type" class="block mt-1 w-full" name="booking_type" required>
                <option value="Full Day">Full Day</option>
                <option value="Half Day">Half Day</option>
                <option value="Custom">Custom</option>
            </x-select>
            <x-input-error :messages="$errors->get('booking_type')" class="mt-2" />
        </div>

        <!-- Booking Slot (visible only for Half Day) -->
        <div class="mt-4" id="booking_slot_container" style="display: none;">
            <x-input-label for="booking_slot" :value="__('Booking Slot')" />
            <x-select id="booking_slot" class="block mt-1 w-full" name="booking_slot">
                <option value="First Half">First Half</option>
                <option value="Second Half">Second Half</option>
            </x-select>
            <x-input-error :messages="$errors->get('booking_slot')" class="mt-2" />
        </div>

        <!-- Booking Time (visible only for Custom booking type) -->
        <div class="mt-4" id="booking_time_container" style="display: none;">
            <x-input-label for="from_time" :value="__('From Time')" />
            <x-time-picker id="from_time" class="block mt-1 w-full" name="from_time" required />

            <x-input-label for="to_time" :value="__('To Time')" />
            <x-time-picker id="to_time" class="block mt-1 w-full" name="to_time" required />

            <x-input-error :messages="$errors->get('from_time')" class="mt-2" />
            <x-input-error :messages="$errors->get('to_time')" class="mt-2" />
        </div>

        <x-primary-button class="mt-4">
            {{ __('Book Now') }}
        </x-primary-button>
    </form>

    <script>
        document.getElementById('booking_type').addEventListener('change', function() {
            const bookingType = this.value;
            const bookingSlotContainer = document.getElementById('booking_slot_container');
            const bookingTimeContainer = document.getElementById('booking_time_container');

            if (bookingType === 'Half Day') {
                bookingSlotContainer.style.display = 'block';
                bookingTimeContainer.style.display = 'none';
            } else if (bookingType === 'Custom') {
                bookingSlotContainer.style.display = 'none';
                bookingTimeContainer.style.display = 'block';
            } else {
                bookingSlotContainer.style.display = 'none';
                bookingTimeContainer.style.display = 'none';
            }
        });
    </script>
</x-guest-layout>
