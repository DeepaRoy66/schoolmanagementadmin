<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_year_runs', function (Blueprint $table) {
            $table->unique(['academic_year_id', 'class_id'], 'ayr_year_class_unique');
        });
    }

    public function down(): void
    {
        Schema::table('academic_year_runs', function (Blueprint $table) {
            $table->dropUnique('ayr_year_class_unique');
        });
    }
};