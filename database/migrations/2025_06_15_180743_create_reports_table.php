<?php

use App\Enums\ReportTypes;
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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('report_type')->default(ReportTypes::TASK_BASED->value)
                ->comment('Type of report, e.g., technical, financial, operational, task_based');
            $table->text('original_log')->nullable();
            $table->text('aggregated_diff')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
