<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('billing_period_id')->nullable()->constrained('billing_periods')->nullOnDelete();
            $table->string('invoice_no')->unique();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('billing_date');
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // unpaid | paid | partially_paid | void
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['school_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};