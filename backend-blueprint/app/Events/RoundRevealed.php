
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

    public function __construct(string $roundId, array $votes)
    {
        $this->roundId = $roundId;
        $this->votes = $votes;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('room.general');
    }

    public function broadcastAs(): string
    {
        return 'round.revealed';
    }
}
