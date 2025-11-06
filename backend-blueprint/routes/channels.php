<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('room.{roomId}', fn($user,$roomId) => ['alias' => substr($user->id ?? 'anon', 0, 8)]);
