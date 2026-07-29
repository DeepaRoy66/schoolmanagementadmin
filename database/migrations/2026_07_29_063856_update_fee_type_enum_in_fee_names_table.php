<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE fee_names MODIFY fee_type ENUM('compulsory_regular', 'extra_miscellaneous', 'optional') DEFAULT 'compulsory_regular'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE fee_names MODIFY fee_type ENUM('one_time', 'recurring') DEFAULT 'recurring'");
    }
};