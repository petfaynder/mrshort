<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('migrations')->where('migration', '2025_06_29_152002_create_permission_tables')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu işlemin geri alınmasına gerek yoktur.
    }
};
