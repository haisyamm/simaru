<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::when(request()->search, function ($bookings) {
            $bookings = $bookings->where('name', 'like', '%' . request()->search . '%');
        })->paginate(10);

        return view('bookings.index', compact('bookings'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        return view('bookings.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bookingDate' => 'required',
            'roomId' => 'required|exists:rooms,id',
        ]);

        try{

        $booking = Booking::create([
            'bookingDate' => $request->bookingDate,
            'roomId' => $request->roomId,
            'userId' => Auth::user()->id,
        ]);

        return redirect()->route('bookings.index')
            ->with('success', 'Room has been booked successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $rooms = Room::all();
        return view('bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bookingDate' => 'required',
            'roomId' => 'required|exists:rooms,id',
        ]);

        try {
            $booking = Booking::find($id);
            // dd($booking->first());
            $booking->bookingDate = $request->bookingDate;
            $booking->roomId = $request->roomId;
            $booking->save();
            return redirect()->route('bookings.index')
            ->with('success', 'The bookings room has been updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        if ($booking) {
            $booking->delete();
            return redirect()->route('bookings.index')
            ->with('success', 'Bookings room has been deleted successfully!');
        } else {
            return back()->with('error', 'Booking not found!');
        }
    }

    public function approve($id)
    {
        try {
            $booking = Booking::find($id);
            $booking->status = "approved";
            $booking->save();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$booking->name.' has been approved successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $booking = Booking::find($id);
            $booking->status = "rejected";
            $booking->save();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$booking->name.' has been rejected successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
