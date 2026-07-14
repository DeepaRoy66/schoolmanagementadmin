<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->string('exam_name');
            $table->string('subject');
            $table->decimal('marks_obtained', 5, 2);
            $table->decimal('full_marks', 5, 2)->default(100);
            $table->string('remarks')->nullable();
            $table->timestamps();

            // Euta student ko euta exam ko euta subject ma euta matra result huna paucha
            $table->unique(['student_id', 'exam_name', 'subject']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};