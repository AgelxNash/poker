
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_id');
            $table->string('name');
            $table->boolean('is_anonymous')->default(true);
            $table->boolean('allow_observers')->default(true);
            $table->uuid('deck_id')->nullable();
            $table->boolean('allow_free_input')->default(false);
            $table->string('consensus_rule')->default('median');
            $table->timestamps();
        });
        Schema::create('room_members', function (Blueprint $table) {
            $table->uuid('room_id');
            $table->uuid('user_id');
            $table->string('role');
            $table->string('alias')->nullable();
            $table->primary(['room_id','user_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('room_members');
        Schema::dropIfExists('rooms');
    }
};
