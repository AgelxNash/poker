
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Session,Round};
use Carbon\Carbon;

class SessionController extends Controller
{
    public function start(Request $req)
    {
        $data = $req->validate([
            'roomId' => 'required|string',
            'issueId' => 'required|string',
            'timeLimit' => 'nullable|integer'
        ]);
        $session = Session::create([
            'id' => (string) Str::uuid(),
            'room_id' => $data['roomId'],
            'issue_id' => $data['issueId'],
            'state' => 'voting',
        ]);
        $round = Round::create([
            'id' => (string) Str::uuid(),
            'session_id' => $session->id,
            'index' => 1,
            'time_limit_seconds' => $data['timeLimit'] ?? null,
            'started_at' => Carbon::now(),
        ]);
        return ['session'=>$session,'round'=>$round];
    }

    public function finalize(Request $req, string $sessionId)
    {
        $data = $req->validate([
            'finalValue' => 'required|string',
            'pushToJira' => 'boolean'
        ]);
        $s = Session::findOrFail($sessionId);
        $s->final_value = $data['finalValue'];
        $s->state = 'finalized';
        $s->save();
        return $s;
    }
}
