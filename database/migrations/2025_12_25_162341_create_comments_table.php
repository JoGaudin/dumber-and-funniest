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
        Schema::create('comment_types', function (Blueprint $table) {
            $table->id('comment_type_id');
            $table->string('name');
            $table->string('emoji');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('comment_type_id')->constrained('comment_types');
            $table->text('comment');
            $table->integer('validated_nb')->default(0);
            $table->integer('revocation_nb')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_types');
        Schema::dropIfExists('comments');
    }
};
