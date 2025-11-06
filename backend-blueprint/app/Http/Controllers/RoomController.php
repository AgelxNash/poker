
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        return Room::query()->orderBy('created_at','desc')->get();
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|string',
            'is_anonymous' => 'boolean',
            'allow_observers' => 'boolean',
            'deck_id' => 'nullable|string',
            'allow_free_input' => 'boolean',
            'consensus_rule' => 'string',
        ]);
        $data['owner_id'] = $req->user()->id ?? (string) Str::uuid();
        $room = Room::create($data);
        DB::table('room_members')->insert([
            'room_id' => $room->id,
            'user_id' => $data['owner_id'],
            'role' => 'host',
            'alias' => 'Host',
        ]);
        return $room;
    }
}
