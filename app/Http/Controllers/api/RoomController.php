<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('category')->when(request()->search, function ($rooms) {
            $rooms = $rooms->where('name', 'like', '%' . request()->search . '%');
            
        });
        if(request()->status) {
            $rooms = $rooms->where('status', request()->status);
        }
        $rooms = $rooms->paginate(10);

        return response()->json($rooms);
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
            'name' => 'required',
            'categoryId' => 'required|exists:categories,id',
            'price' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $room = Room::create([
            'name' => $request->name,
            'categoryId' => $request->categoryId,
            'userId' => $request->userId,
            'price' => $request->price,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        return response()->json([
            "message" => "Data Berhasil di Simpan",
            "data" => $room
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $room = Room::find($id)->with('category')->first();
        return response()->json($room);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'categoryId' => 'required|exists:categories,id',
            'price' => 'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $room = Room::find($id);
        $room->name = $request->name;
        $room->categoryId = $request->categoryId;
        $room->price = $request->price;
        $room->capacity = $request->capacity;
        $room->description = $request->description;
        $room->save();


        return response()->json([
            "message" => "Data Berhasil di Simpan",
            "data" => $room
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        if($room){
            $room->delete();
            return response()->json([
                "message" => "Data Berhasil di Hapus",
            ], 200);
        }else{
            return response()->json([
                "message" => "Data Tidak Ditemukan",
            ], 404);
        }
    }
}
