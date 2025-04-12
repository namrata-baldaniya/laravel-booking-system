@extends('layouts.booking_layout')

@section('content')

<!-- <body class="bg-gray-100 py-10"> -->
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Create Booking</h2>

        @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('bookings.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('customer_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">Customer Email</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('customer_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Booking Date</label>
                    <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('booking_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="booking_type" class="block text-sm font-medium text-gray-700 mb-1">Booking Type</label>
                    <select name="booking_type" id="booking_type" onchange="toggleFields()"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Type --</option>
                        <option value="Full Day" {{ old('booking_type') == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                        <option value="Half Day" {{ old('booking_type') == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                        <option value="Custom" {{ old('booking_type') == 'Custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    @error('booking_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('booking_slot')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                </div>
            </div>

            <div id="slot_section" class="mt-6 hidden">
                <label for="booking_slot" class="block text-sm font-medium text-gray-700 mb-1">Booking Slot</label>
                <select name="booking_slot" id="booking_slot"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Slot --</option>
                    <option value="First Half" {{ old('booking_slot') == 'First Half' ? 'selected' : '' }}>First Half</option>
                    <option value="Second Half" {{ old('booking_slot') == 'Second Half' ? 'selected' : '' }}>Second Half</option>
                </select>
                
            </div>

            <div id="time_section" class="mt-6 hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="from_time" class="block text-sm font-medium text-gray-700 mb-1">From Time</label>
                    <input type="time" name="from_time" id="from_time" value="{{ old('from_time') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                    @error('from_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="to_time" class="block text-sm font-medium text-gray-700 mb-1">To Time</label>
                    <input type="time" name="to_time" id="to_time" value="{{ old('to_time') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('to_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-between items-center">
                <a href="{{ route('bookings.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Back to List</a>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    Submit Booking
                </button>
            </div>
        </form>
    </div>
<!-- </body> -->

<!-- </html> -->
@endsection

<script>
        function toggleFields() {
            const type = document.getElementById('booking_type').value;
            document.getElementById('slot_section').style.display = (type === 'Half Day') ? 'block' : 'none';
            document.getElementById('time_section').style.display = (type === 'Custom') ? 'grid' : 'none';
        }

        window.addEventListener('DOMContentLoaded', toggleFields);
    </script>