<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'name')) {
                $table->dropColumn('name');
            }

            if (!Schema::hasColumn('students', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            if (!Schema::hasColumn('students', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('students', 'last_name')) {
                $table->string('last_name')->after('middle_name');
            }
            if (!Schema::hasColumn('students', 'dob')) {
                $table->date('dob')->nullable()->after('last_name');
            }
            
            if (!Schema::hasColumn('students', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
            }
            if (!Schema::hasColumn('students', 'student_uid')) {
                $table->string('student_uid', 3)->unique()->nullable()->after('gender');
            }
            if (!Schema::hasColumn('students', 'status')) {
                $table->enum('status', ['active', 'inactive', 'dropped_out'])->default('active')->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'middle_name', 'last_name', 'dob', 'gender', 'student_uid', 'status']);
        });
    }
};