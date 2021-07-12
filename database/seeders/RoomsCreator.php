<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class RoomsCreator extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create_rooms = 50;

        for ($i = 0; $i <= $create_rooms; $i++) {
            $r = new Room();
            $r->room_number = Str::upper(Str::random(3));
            $r->save();
        }
    }
}
