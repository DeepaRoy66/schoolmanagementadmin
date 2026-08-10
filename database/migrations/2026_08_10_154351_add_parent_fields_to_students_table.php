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
    Schema::table('students', function (Blueprint $table) {
        $table->string('parent_name')->nullable();
        $table->string('parent_phone')->nullable();
        $table->string('telephone_no')->nullable();
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn(['parent_name', 'parent_phone', 'telephone_no']);
    });
}
};
