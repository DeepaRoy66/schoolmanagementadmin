<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_teacher_assignments', function (Blueprint $table) {
            // section_id ko FK constraint le unique(section_id) index use gardai
            // thiyo, so pahile FK hataune, ani index hataune - natra MySQL le
            // "needed in a foreign key constraint" error dincha.
            $table->dropForeign(['section_id']);
            $table->dropUnique(['section_id']);

            // section_id ko FK lai feri index chahincha (composite unique tala
            // section_id bata suru nahune bhayera FK ko lagi kaam gardaina),
            // so separate plain index rakhne, ani composite unique add garne.
            $table->index('section_id');
            $table->unique(['class_id', 'section_id']);

            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('class_teacher_assignments', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropUnique(['class_id', 'section_id']);
            $table->dropIndex(['section_id']);

            $table->unique('section_id');
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnDelete();
        });
    }
};