<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class DeckCard extends Model {
    use UsesUuid;
    public $timestamps = false;
    protected $fillable = ['deck_id','value','sort_order'];
}