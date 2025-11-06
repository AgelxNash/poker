
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jira_connections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_id');
            $table->string('cloud_base_url');
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        Schema::create('issues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id');
            $table->uuid('jira_connection_id')->nullable();
            $table->string('jira_issue_key');
            $table->string('summary')->nullable();
            $table->string('assignee')->nullable();
            $table->string('status')->nullable();
            $table->string('story_points_field')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('issues');
        Schema::dropIfExists('jira_connections');
    }
};
