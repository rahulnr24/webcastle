<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function available_rooms()
    {
        $rooms = Room::paginate();

        $data = [
            'status' => 200,
            'message' => 'Fetched rooms',
            'data' => $rooms
        ];

        return response()->json($data, $data['status']);
    }

    public function book_room(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'room_number' => 'required|exists:rooms'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            $room = Room::where(['availability' => true, 'room_number' => $request->room_number])->first();

            if ($room) {
                $booking = new Booking();
                $booking->user()->associate($request->get('user_id'));
                $booking->room()->associate($room);
                $booking->save();

                $room->availability = false;
                $room->save();

                $booking_details = ['booking_number' => $booking->booking_number];

                $data = [
                    'status' => 200,
                    'message' => 'Room booked',
                    'data' => $booking_details
                ];
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Room is already taken'
                ];
            }
        }

        return response()->json($data, $data['status']);
    }

    public function vacate_room(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'booking_number' => 'required|exists:bookings'
        ]);

        if ($validator->fails()) {
            $data = [
                'status' => 422,
                'message' => $validator->errors()
            ];
        } else {

            $booking = Booking::where(['booking_number' => $request->booking_number])
                ->whereNull('vacated_at')->first();

            if ($booking) {

                $booking->vacated_at = now();
                $booking->save();

                $booking->room()->update(['availability' => true]);

                $data = [
                    'status' => 200,
                    'message' => 'Room vacated successfully'
                ];
            } else {
                $data = [
                    'status' => 400,
                    'message' => 'Already vacated'
                ];
            }
        }

        return response()->json($data, $data['status']);
    }
}
