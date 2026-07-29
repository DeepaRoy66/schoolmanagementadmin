<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('fee_name_id')->constrained('fee_names')->cascadeOnDelete();
            $table->foreignId('billing_period_id')->nullable()->constrained('billing_periods')->nullOnDelete();
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();

            // Euta student + fee name + billing period ko combination euta matra
            $table->unique(['student_id', 'fee_name_id', 'billing_period_id'], 'fee_discount_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_discounts');
    }
};