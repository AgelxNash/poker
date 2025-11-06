
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
        Vote::updateOrCreate(
            ['round_id'=>$roundId,'user_id'=>$uid],
            ['value'=>$data['value'],'created_at'=>Carbon::now()]
        );
        event(new VoteStatusChanged($roundId, $uid, 'voted'));
        return ['ok'=>true];
    }

    public function reveal(Request $req, string $roundId)
    {
        $round = Round::findOrFail($roundId);
        $round->revealed_at = Carbon::now();
        $round->save();
        $votes = Vote::where('round_id',$roundId)->pluck('value')->all();
        event(new RoundRevealed($roundId, $votes));
        return ['ok'=>true,'votes'=>$votes];
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
        ]);
        return ['round'=>$new];
    }
}
