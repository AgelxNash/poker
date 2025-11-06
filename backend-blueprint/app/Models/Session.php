<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Session extends Model {
    use UsesUuid;
    protected $fillable = ['room_id','issue_id','state','final_value'];
}