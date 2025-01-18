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
        Schema::create('tags', function (Blueprint $table) {
            $table->string('label')->primary();
        });

        Schema::create('taggable', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->morphs('taggable');
            $table->timestamps();

            $table->foreign('tag')->references('label')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggable_tables');
    }
};
