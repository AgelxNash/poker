<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Session,Round,Vote};
use App\Services\ConsensusService;
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
            'deadline_at' => isset($data['timeLimit']) ? Carbon::now()->addSeconds((int)$data['timeLimit']) : null,
        ]);
        if ($round->time_limit_seconds) {
            \App\Jobs\CloseRoundIfExpired::dispatch($round->id)->delay(now()->addSeconds($round->time_limit_seconds));
        }
        return ['session'=>$session,'round'=>$round];
    }

    public function finalize(Request $req, string $sessionId)
    {
        $data = $req->validate([
            'finalValue' => 'nullable|string',
            'pushToJira' => 'boolean'
        ]);
        $s = Session::findOrFail($sessionId);
        if (empty($data['finalValue'])) {
            $lastRound = \App\Models\Round::where('session_id',$s->id)->orderByDesc('index')->first();
            $vals = $lastRound ? Vote::where('round_id',$lastRound->id)->pluck('value')->all() : [];
            $svc = app(ConsensusService::class);
            $median = $svc->median($vals, []);
            $final = is_null($median) ? null : (string)$svc->roundToDeck($median, [0,0.5,1,2,3,5,8,13,20,40,100]);
            $s->final_value = $final ?? 'N/A';
        } else {
            $s->final_value = $data['finalValue'];
        }
        $s->state = 'finalized';
        $s->save();
        return $s;
    }
}
