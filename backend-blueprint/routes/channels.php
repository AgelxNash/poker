
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    return ['alias' => substr($user->id ?? 'anon', 0, 8)];
});
