<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();

            // Adjust these two FKs to match your actual schema/column names.
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
            // "class_id" plays the role of "Program Name" in the reference screenshot
            // (e.g. Bsc.CSIT, ONE, Robotics). If periods in your school are NOT tied
            // to a specific class, just drop this column and the relation in the model.

            $table->string('name');           // Period Name  e.g. "1st Period", "Lunch"
            $table->string('code');           // Period Code  e.g. "p1", "L-1"
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_break')->default(false);   // shows "Break" badge
            $table->boolean('is_active')->default(true);   // Active / Inactive
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};