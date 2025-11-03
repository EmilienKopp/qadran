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
        Schema::table('known_issues', function (Blueprint $table) {
            $table->boolean('is_deployed')->default(false)->after('metadata');
            $table->jsonb('deployment_metadata')->nullable()->after('is_deployed');
            $table->timestamp('deployed_at')->nullable()->after('deployment_metadata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('known_issues', function (Blueprint $table) {
            $table->dropColumn(['is_deployed', 'deployment_metadata', 'deployed_at']);
        });
    }
};
