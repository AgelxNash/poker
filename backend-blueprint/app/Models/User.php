<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\UsesUuid;

class User extends Authenticatable {
    use HasApiTokens, Notifiable, UsesUuid;
    protected $fillable = ['email','display_name','avatar_url','jira_account_id'];
    protected $hidden = ['password','remember_token'];
}