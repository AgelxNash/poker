
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id');
            $table->uuid('issue_id');
            $table->string('state');
            $table->string('final_value')->nullable();
            $table->timestamps();
        });
        Schema::create('rounds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->integer('index');
            $table->integer('time_limit_seconds')->nullable();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('revealed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
        });
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('round_id');
            $table->uuid('user_id');
            $table->string('value');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['round_id','user_id']);
        });
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id');
            $table->json('payload');
            $table->timestamp('generated_at')->useCurrent();
            $table->string('storage_url')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('votes');
        Schema::dropIfExists('rounds');
        Schema::dropIfExists('sessions');
    }
};
