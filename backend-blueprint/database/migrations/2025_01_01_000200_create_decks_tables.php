
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('decks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id')->nullable();
            $table->string('name');
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });
        Schema::create('deck_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('deck_id');
            $table->string('value');
            $table->integer('sort_order');
        });
    }
    public function down(): void {
        Schema::dropIfExists('deck_cards');
        Schema::dropIfExists('decks');
    }
};
