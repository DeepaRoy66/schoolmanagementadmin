<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'class_teacher_of_class')) {
                $table->string('class_teacher_of_class')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('teachers', 'class_teacher_of_section')) {
                $table->string('class_teacher_of_section')->nullable()->after('class_teacher_of_class');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['class_teacher_of_class', 'class_teacher_of_section']);
        });
    }
};