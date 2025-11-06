<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Deck extends Model {
    use UsesUuid;
    protected $fillable = ['room_id','name','is_system'];
}