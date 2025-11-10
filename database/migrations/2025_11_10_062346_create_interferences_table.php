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
        Schema::create('interferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('clock_entry_id')->nullable()->constrained('clock_entries')->onDelete('set null');
            $table->dateTimeTz('in')->nullable();
            $table->dateTimeTz('out')->nullable();
            $table->string('timezone')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->integer('duration_seconds')->nullable()->after('out')->storedAs('EXTRACT(EPOCH FROM ("out" - "in"))');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interferences');
    }
};
