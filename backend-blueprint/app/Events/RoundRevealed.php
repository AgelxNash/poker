<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundRevealed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roundId;
    public array $votes;
    public string $roomId;
    /** @var array{histogram: array<string,int>, numeric: array{count:int,min:float|null,max:float|null,avg:float|null,median:float|null,stdev:float|null}} */
    public array $stats;

    public function __construct(string $roundId, string $roomId, array $votes, array $stats = [])
    {
        $this->roundId = $roundId;
        $this->roomId = $roomId;
        $this->votes = $votes;
        $this->stats = $stats;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('room.' . $this->roomId);
    }

    public function broadcastAs(): string
    {
        return 'round.revealed';
    }
}
