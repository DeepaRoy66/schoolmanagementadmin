<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->after('teacher_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->after('class_id')->constrained('sections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_id');
            $table->dropConstrainedForeignId('section_id');
        });
    }
};