<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = ['round_id','user_id','value','created_at'];
}