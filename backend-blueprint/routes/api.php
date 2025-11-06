
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{RoomController, SessionController, RoundController};

Route::get('/rooms', [RoomController::class,'index']);
Route::post('/rooms', [RoomController::class,'store']);

Route::post('/sessions', [SessionController::class,'start']);
Route::post('/sessions/{sessionId}/finalize', [SessionController::class,'finalize']);

Route::post('/rounds/{roundId}/vote', [RoundController::class,'vote']);
Route::post('/rounds/{roundId}/reveal', [RoundController::class,'reveal']);
Route::post('/rounds/{roundId}/revote', [RoundController::class,'revote']);
