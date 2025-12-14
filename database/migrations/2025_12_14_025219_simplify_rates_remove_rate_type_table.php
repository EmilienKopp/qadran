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
        // Drop the foreign key constraint and rate_type_id column
        Schema::table('rates', function (Blueprint $table) {
            if (Schema::hasColumn('rates', 'rate_type_id')) {
                $table->dropForeign(['rate_type_id']);
                $table->dropColumn('rate_type_id');
            }
        });

        // Make rate_type column required if it's not already
        Schema::table('rates', function (Blueprint $table) {
            if (Schema::hasColumn('rates', 'rate_type')) {
                $table->string('rate_type')->nullable(false)->change();
            }
        });

        // Drop the rate_types table as it's no longer needed
        // We now use the RateType enum directly
        Schema::dropIfExists('rate_types');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the rate_types table
        Schema::create('rate_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('scope')->default('organization');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Restore rate_type_id column and make rate_type nullable
        Schema::table('rates', function (Blueprint $table) {
            $table->string('rate_type')->nullable()->change();
            $table->foreignId('rate_type_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
