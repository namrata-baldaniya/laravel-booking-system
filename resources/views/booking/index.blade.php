@extends('layouts.booking_layout')

@section('content')

    <div class="max-w-6xl mx-auto px-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Bookings List</h1>
            <a href="{{ route('bookings.create') }}" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                + Create Booking
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Booking Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Customer Name</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Type</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">Slot</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">From</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-700">To</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $booking->customer_name }}</td>
                            <td class="px-6 py-4">{{ $booking->customer_email }}</td>
                            <td class="px-6 py-4">{{ $booking->booking_date }}</td>
                            <td class="px-6 py-4">{{ $booking->booking_type }}</td>
                            <td class="px-6 py-4">{{ $booking->booking_slot ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $booking->from_time ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $booking->to_time ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection