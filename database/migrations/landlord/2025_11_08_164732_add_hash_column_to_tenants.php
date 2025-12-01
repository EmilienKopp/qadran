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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('hash')->nullable();
            $table->jsonb('n8n_webhooks')->nullable();
        });

        $tenants = \App\Models\Landlord\Tenant::all();
        foreach ($tenants as $tenant) {
            $hash = hash('sha256', $tenant->id.env('APP_KEY'));
            $tenant->hash = $hash;
            $tenant->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('hash');
        });
    }
};
