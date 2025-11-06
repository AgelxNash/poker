<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $roundId;
    public string $userIdMasked;
    public string $status;
    public string $roomId;

    public function __construct(string $roundId, string $roomId, string $userId, string $status)
    {
        $this->roundId = $roundId;
        $this->roomId = $roomId;
        $this->userIdMasked = substr($userId, 0, 8);
        $this->status = $status;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('room.' . $this->roomId);
    }

    public function broadcastAs(): string
    {
        return 'vote.status';
    }
}
