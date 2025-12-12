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
        DB::connection('landlord')->unprepared(<<<SQL
            ALTER DATABASE tenant_template WITH IS_TEMPLATE true;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('landlord')->unprepared(<<<SQL
            ALTER DATABASE tenant_template WITH IS_TEMPLATE false;
        SQL);
    }
};
