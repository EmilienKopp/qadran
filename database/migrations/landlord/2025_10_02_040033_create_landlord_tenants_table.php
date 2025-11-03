<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Landlord\Tenant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('host')->unique();
            $table->string('database')->unique();
            $table->string('org_id')->unique();
            $table->timestamps();
        });

        // Create main tenant
        // Tenant::create([
        //     'id' => str()->uuid(),
        //     'name' => 'Qadran.io Main Tenant',
        //     'domain' => 'qadranio.com',
        //     'host' => 'qadranio',
        //     'database' => 'qadran_db',
        //     'org_id' => env('DEFAULT_ORG_ID'),
        // ]);
    }
};
