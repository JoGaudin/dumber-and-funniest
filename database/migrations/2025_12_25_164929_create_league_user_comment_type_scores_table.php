<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('league_user_comment_type_score', function (Blueprint $table) {
            $table->foreignId('league_user_id')->constrained('league_user');
            $table->foreignId('comment_type_id')->constrained('comment_types');
            $table->integer('score')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('league_user_comment_type_score');
    }
};
