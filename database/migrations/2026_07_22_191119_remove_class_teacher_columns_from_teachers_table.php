<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveClassTeacherColumnsFromTeachersTable extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'class_teacher_of_class',
                'class_teacher_of_section',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->string('class_teacher_of_class')->nullable();
            $table->string('class_teacher_of_section')->nullable();
        });
    }
}