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
        Schema::create('user_has_exhibitions', function (Blueprint $table) {
            $table->id('id');
            $table->string('user_id', 16);
            $table->string('exhibition_id');
            $table->string('exhibitions_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_has_exhibitions');
    }
};
