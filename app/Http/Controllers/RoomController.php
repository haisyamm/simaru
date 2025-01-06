<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::when(request()->search, function ($rooms) {
            $rooms = $rooms->where('name', 'like', '%' . request()->search . '%');
        })->paginate(10);

        return view('rooms.index', compact('rooms'))
        ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('rooms.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'categoryId' => 'required|exists:categories,id',
            'price' => 'required|numeric'
        ]);

        try{

        $room = Room::create([
            'name' => $request->name,
            'categoryId' => $request->categoryId,
            'userId' => Auth::user()->id,
            'price' => $request->price,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('rooms.index')
            ->with('success', 'Room '.$room->name.' has been added successfully!');
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
    public function edit(Room $room)
    {
        $categories = Category::all();
        return view('rooms.edit', compact('room', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'categoryId' => 'required|exists:categories,id',
            'price' => 'required|numeric'
        ]);

        try {
            $room = Room::find($id);
            // dd($room->first());
            $room->name = $request->name;
            $room->categoryId = $request->categoryId;
            $room->price = $request->price;
            $room->capacity = $request->capacity;
            $room->description = $request->description;
            $room->save();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$room->name.' has been updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if ($room) {
            $room->delete();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$room->name.' has been deleted successfully!');
        } else {
            return back()->with('error', 'Room not found!');
        }
    }

    public function approve($id)
    {
        try {
            $room = Room::find($id);
            $room->status = "approved";
            $room->save();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$room->name.' has been approved successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function reject($id)
    {
        try {
            $room = Room::find($id);
            $room->status = "rejected";
            $room->save();
            return redirect()->route('rooms.index')
            ->with('success', 'Room '.$room->name.' has been rejected successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
