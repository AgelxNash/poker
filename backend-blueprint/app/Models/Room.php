<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Room extends Model {
    use UsesUuid;
    protected $fillable = ['owner_id','name','is_anonymous','allow_observers','deck_id','allow_free_input','consensus_rule'];
}