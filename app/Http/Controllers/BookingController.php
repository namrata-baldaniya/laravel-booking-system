<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    //
    // public function index()
    // {
    //     $bookings = Booking::latest()->paginate(10);
    //     return view('booking.index', compact('bookings'));
    // }
    public function index(Request $request)
    {
        $bookings= Booking::select('customer_name', 'customer_email', 'booking_date', 'booking_type', 'booking_slot', 'from_time', 'to_time')
                ->orderBy('booking_date', 'desc')
                ->paginate(10);

        return view('booking.index', compact('bookings'));
    }


    public function create()
    {
        return view('booking.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'booking_date' => 'required|date',
            'booking_type' => 'required|in:Full Day,Half Day,Custom',
            'booking_slot' => 'nullable|in:First Half,Second Half',
            'from_time' => 'nullable|date_format:H:i',
            'to_time' => 'nullable|date_format:H:i|after:from_time',
        ]);
    
        $bookingDate = $request->booking_date;
        $bookingType = $request->booking_type;
        $bookingSlot = $request->booking_slot;
        $fromTime = $request->from_time;
        $toTime = $request->to_time;
    
        if ($bookingType == 'Full Day') {
            $existingHalfDayBooking = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Half Day')
                ->exists();
    
            $existingCustomBooking = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Custom')
                ->exists();
    
            if ($existingHalfDayBooking) {
                return back()->withErrors(['booking_date' => 'This day already has a Half Day booking. A Full Day booking is not allowed.']);
            }
    
            if ($existingCustomBooking) {
                return back()->withErrors(['booking_date' => 'This day already has a Custom booking. A Full Day booking is not allowed.']);
            }
    
            $existingBooking = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Full Day')
                ->exists();
    
            if ($existingBooking) {
                return back()->withErrors(['booking_date' => 'This day is already fully booked for a full-day session.']);
            }
        }
    
        if ($bookingType == 'Half Day' && $bookingSlot == 'First Half') {
            $existingFullDay = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Full Day')
                ->exists();
    
            if ($existingFullDay) {
                return back()->withErrors(['booking_date' => 'Full Day booking is not allowed as First Half is already booked.']);
            }
            $existingCustomFirstHalf = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Custom')
                ->where(function($query) use ($fromTime, $toTime) {
                    $query->whereBetween('from_time', [$fromTime, $toTime])
                        ->orWhereBetween('to_time', [$fromTime, $toTime]);
                })
                ->exists();

            if ($existingCustomFirstHalf) {
                return back()->withErrors(['booking_slot' => 'The First Half is already booked for a Custom session.']);
            }
    
            $existingHalfDayFirstHalf = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Half Day')
                ->where('booking_slot', 'First Half')
                ->exists();
    
            if ($existingHalfDayFirstHalf) {
                return back()->withErrors(['booking_slot' => 'First Half is already booked for Half Day.']);
            }
        }
    
        if ($bookingType == 'Half Day' && $bookingSlot == 'Second Half') {
            $existingFullDay = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Full Day')
                ->exists();
    
            if ($existingFullDay) {
                return back()->withErrors(['booking_date' => 'Full Day booking is not allowed as Second Half is already booked.']);
            }

            $existingCustomSecondHalf = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Custom')
                ->where(function($query) use ($fromTime, $toTime) {
                    $query->whereBetween('from_time', [$fromTime, $toTime])
                        ->orWhereBetween('to_time', [$fromTime, $toTime]);
                })
                ->exists();

            if ($existingCustomSecondHalf) {
                return back()->withErrors(['booking_slot' => 'The Second Half is already booked for a Custom session.']);
            }
    
            $existingHalfDaySecondHalf = Booking::where('booking_date', $bookingDate)
                ->where('booking_type', 'Half Day')
                ->where('booking_slot', 'Second Half')
                ->exists();
    
            if ($existingHalfDaySecondHalf) {
                return back()->withErrors(['booking_slot' => 'Second Half is already booked for Half Day.']);
            }
        }
    
        if ($bookingType == 'Custom') {
            $fromTimeHour = (int) date('H', strtotime($fromTime));
    
            if ($fromTimeHour < 12) {
                $existingFullDay = Booking::where('booking_date', $bookingDate)
                    ->where('booking_type', 'Full Day')
                    ->exists();
    
                $existingHalfDay = Booking::where('booking_date', $bookingDate)
                    ->where('booking_type', 'Half Day')
                    ->where('booking_slot', 'First Half')
                    ->exists();
    
                if ($existingFullDay) {
                    return back()->withErrors(['booking_date' => 'This day is already fully booked for a full-day session.']);
                }
    
                if ($existingHalfDay) {
                    return back()->withErrors(['booking_slot' => 'The First Half is already booked for Half Day.']);
                }
            } else { 
                $existingFullDay = Booking::where('booking_date', $bookingDate)
                    ->where('booking_type', 'Full Day')
                    ->exists();
    
                $existingHalfDay = Booking::where('booking_date', $bookingDate)
                    ->where('booking_type', 'Half Day')
                    ->where('booking_slot', 'Second Half')
                    ->exists();
    
                if ($existingFullDay) {
                    return back()->withErrors(['booking_date' => 'This day is already fully booked for a full-day session.']);
                }
    
                if ($existingHalfDay) {
                    return back()->withErrors(['booking_slot' => 'The Second Half is already booked for Half Day.']);
                }
            }
        }
    
        Booking::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'booking_date' => $bookingDate,
            'booking_type' => $bookingType,
            'booking_slot' => $bookingSlot,
            'from_time' => $fromTime,
            'to_time' => $toTime,
        ]);
    
        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }
    
    
}
