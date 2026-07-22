<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subject_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();

            // 'subjects' table ma pahile nai class_id cha (Subject = class-specific),
            // so yaha class_id doharyaunu pardaina - subject_id le implicitly class define garcha
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();

            // Same subject (same class) ko different section ma alag teacher huna sakcha
            // (e.g. Class 3-A Math -> Ram Sir, Class 3-B Math -> Sita Miss)
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();

            $table->timestamps();

            // Euta subject + euta section ko combination ma euta matra teacher huna paucha
            $table->unique(['subject_id', 'section_id'], 'unique_subject_section');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_allocations');
    }
};
