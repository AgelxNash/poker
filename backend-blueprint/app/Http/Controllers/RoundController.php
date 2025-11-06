<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Round, Vote, Session};
use App\Events\{VoteStatusChanged, RoundRevealed};
use Carbon\Carbon;

class RoundController extends Controller
{
    public function vote(Request $req, string $roundId)
    {
        $data = $req->validate([ 'value' => 'required|string' ]);
        $uid = $req->user()->id ?? (string) Str::uuid();
        $session = Session::findOrFail(Round::findOrFail($roundId)->session_id);
        $roomId = $session->room_id;
        Vote::updateOrCreate(
            ['round_id'=>$roundId,'user_id'=>$uid],
            ['value'=>$data['value'],'created_at'=>Carbon::now()]
        );
        event(new VoteStatusChanged($roundId, $roomId, $uid, 'voted'));
        return ['ok'=>true];
    }

    public function reveal(Request $req, string $roundId)
    {
        $round = Round::findOrFail($roundId);
        $session = Session::findOrFail($round->session_id);
        $roomId = $session->room_id;
        $round->revealed_at = Carbon::now();
        $round->save();
        $votes = Vote::where('round_id',$roundId)->pluck('value')->all();

        $hist = [];
        $nums = [];
        foreach ($votes as $v) {
            $hist[$v] = ($hist[$v] ?? 0) + 1;
            if (is_numeric($v)) { $nums[] = (float)$v; }
        }
        sort($nums);
        $count = count($nums);
        $min = $count ? min($nums) : null;
        $max = $count ? max($nums) : null;
        $avg = $count ? array_sum($nums)/$count : null;
        $median = null;
        if ($count) {
            $mid = intdiv($count,2);
            $median = ($count % 2 === 0) ? (($nums[$mid-1]+$nums[$mid])/2.0) : $nums[$mid];
        }
        $stdev = null;
        if ($count > 1) {
            $var = array_sum(array_map(fn($x)=>($x-$avg)**2, $nums))/($count-1);
            $stdev = sqrt($var);
        }
        $stats = [
            'histogram' => $hist,
            'numeric' => [
                'count'=>$count,'min'=>$min,'max'=>$max,'avg'=>$avg,'median'=>$median,'stdev'=>$stdev
            ]
        ];

        event(new RoundRevealed($roundId, $roomId, $votes, $stats));
        return ['ok'=>true,'votes'=>$votes,'stats'=>$stats];
    }

    public function revote(Request $req, string $roundId)
    {
        $round = Round::findOrFail($roundId);
        $session = Session::findOrFail($round->session_id);
        $i = ($round->index ?? 1) + 1;
        $new = Round::create([
            'id' => (string) Str::uuid(),
            'session_id' => $session->id,
            'index' => $i,
            'time_limit_seconds' => $round->time_limit_seconds,
            'started_at' => Carbon::now(),
            'deadline_at' => $round->time_limit_seconds ? Carbon::now()->addSeconds($round->time_limit_seconds) : null,
        ]);
        if ($new->time_limit_seconds) {
            \App\Jobs\CloseRoundIfExpired::dispatch($new->id)->delay(now()->addSeconds($new->time_limit_seconds));
        }
        return ['round'=>$new];
    }
}
