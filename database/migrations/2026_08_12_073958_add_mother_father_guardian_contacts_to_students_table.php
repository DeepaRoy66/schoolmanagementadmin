<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('local_guardian_name')->nullable();
            $table->string('local_guardian_phone')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'mother_name',
                'mother_phone',
                'father_name',
                'father_phone',
                'local_guardian_name',
                'local_guardian_phone',
            ]);
        });
    }
};