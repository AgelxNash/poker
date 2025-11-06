<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Round extends Model {
    use UsesUuid;
    public $timestamps = false;
    protected $fillable = ['session_id','index','time_limit_seconds','started_at','revealed_at','closed_at'];
    protected $dates = ['started_at','revealed_at','closed_at'];
}