<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'class_id')) {
                $table->dropForeign(['class_id']);
                $table->dropColumn('class_id');
            }
        });

      
        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'section_id')) {
                $table->foreignId('section_id')->nullable()->after('name')
                    ->constrained('sections')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('class_id')->nullable()->constrained('classes')->cascadeOnDelete();
        });
    }
};