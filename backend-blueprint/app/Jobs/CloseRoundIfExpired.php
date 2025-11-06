<?php

namespace App\Jobs;

use App\Models\Round;
use App\Models\Session;
use App\Models\Vote;
use App\Events\RoundRevealed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CloseRoundIfExpired implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $roundId;

    public function __construct(string $roundId)
    {
        $this->roundId = $roundId;
    }

    public function handle(): void
    {
        $round = Round::find($this->roundId);
        if (!$round) return;
        if ($round->revealed_at) return;
        if (!$round->deadline_at) return;
        if (Carbon::now()->lt(Carbon::parse($round->deadline_at))) return;

        $round->revealed_at = Carbon::now();
        $round->save();

        $votes = Vote::where('round_id',$round->id)->pluck('value')->all();
        $session = Session::findOrFail($round->session_id);

        $hist = [];
        $nums = [];
        foreach ($votes as $v) { $hist[$v]=($hist[$v]??0)+1; if (is_numeric($v)) $nums[]=(float)$v; }
        sort($nums);
        $count = count($nums);
        $avg = $count? array_sum($nums)/$count : null;
        $stdev = ($count>1)? sqrt(array_sum(array_map(fn($x)=>($x-$avg)**2,$nums))/($count-1)) : null;
        $stats = ['histogram'=>$hist,'numeric'=>['count'=>$count,'min'=>$count?min($nums):null,'max'=>$count?max($nums):null,'avg'=>$avg,'median'=>null,'stdev'=>$stdev]];

        event(new RoundRevealed($round->id, $session->room_id, $votes, $stats));
    }
}
