<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'name')) {
                $table->dropColumn('name');
            }

            if (!Schema::hasColumn('teachers', 'first_name')) {
                $table->string('first_name')->after('user_id');
            }
            if (!Schema::hasColumn('teachers', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('teachers', 'last_name')) {
                $table->string('last_name')->after('middle_name');
            }
            if (!Schema::hasColumn('teachers', 'dob')) {
                $table->date('dob')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('teachers', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('dob');
            }
            if (!Schema::hasColumn('teachers', 'marital_status')) {
                $table->enum('marital_status', ['single', 'married', 'other'])->nullable()->after('gender');
            }
            if (!Schema::hasColumn('teachers', 'pan_no')) {
                $table->string('pan_no')->nullable()->after('marital_status');
            }
            if (!Schema::hasColumn('teachers', 'address')) {
                $table->text('address')->nullable()->after('pan_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            foreach (['first_name', 'middle_name', 'last_name', 'dob', 'gender', 'marital_status', 'pan_no', 'address'] as $column) {
                if (Schema::hasColumn('teachers', $column)) {
                    $table->dropColumn($column);
                }
            }
            if (!Schema::hasColumn('teachers', 'name')) {
                $table->string('name')->nullable();
            }
        });
    }
};