<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with('room')->when(request()->search, function ($bookings) {
            $bookings = $bookings->where('name', 'like', '%' . request()->search . '%');
        })->where('userId' , Auth::user()->id)->paginate(10);

        return response()->json($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookingDate' => 'required',
            'roomId' => 'required|exists:rooms,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {

            $booking = Booking::create([
                'bookingDate' => $request->bookingDate,
                'roomId' => $request->roomId,
                'userId' => Auth::user()->id,
            ]);

            return response()->json([
                "message" => "Data Berhasil di simpan",
                "data" => $booking
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data gagal di simpan",
                "data" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::find($id)->with('room')->first();
        return response()->json($booking);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'bookingDate' => 'required',
            'roomId' => 'required|exists:rooms,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        try {
            $booking = Booking::find($id);
            // dd($booking->first());
            $booking->bookingDate = $request->bookingDate;
            $booking->roomId = $request->roomId;
            $booking->save();
            return response()->json([
                "message" => "Data Berhasil di update",
                "data" => $booking
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "Data gagal di update",
                "data" => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        if ($booking) {
            $booking->delete();
            return response()->json([
                "message" => "Data Berhasil di hapus",
                "data" => $booking
            ], 200);
        } else {
            return response()->json([
                "message" => "Data gagal di hapus",
                "error" => "Booking not found!"
            ], 400);
        }
    }

    public function approve($id)
    {
        try {
            $booking = Booking::find($id);
            $booking->status = "approved";
            $booking->save();
            return redirect()->route('rooms.index')
                ->with('success', 'Room ' . $booking->name . ' has been approved successfully!');
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
                ->with('success', 'Room ' . $booking->name . ' has been rejected successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
