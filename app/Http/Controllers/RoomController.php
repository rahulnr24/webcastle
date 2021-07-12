<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function add_room(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'room_number' => 'required|unique:rooms',
            'availability' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            $inputs = $validator->validated();
            $room = Room::create($inputs);

            $data = [
                'status' => 200,
                'message' => 'Created a room',
                'data' => $room
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function update_available_rooms(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'room_number' => 'required|exists:rooms',
            'availability' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            Room::where([
                'room_number' => $request->room_number
            ])->update([
                'availability' => $request->availability
            ]);

            $data = [
                'status' => 200,
                'message' => 'Updated room availability'
            ];
        }

        return response()->json($data, $data['status']);
    }
}
