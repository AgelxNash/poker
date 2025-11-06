<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UsesUuid;

class Issue extends Model {
    use UsesUuid;
    protected $fillable = ['room_id','jira_connection_id','jira_issue_key','summary','assignee','status','story_points_field'];
}