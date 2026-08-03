<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            if (!Schema::hasColumn('student_fees', 'fee_name_id')) {
                $table->foreignId('fee_name_id')->nullable()->after('student_id')
                    ->constrained('fee_names')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('student_fees', 'billing_period_id')) {
                $table->foreignId('billing_period_id')->nullable()->after('fee_name_id')
                    ->constrained('billing_periods')->nullOnDelete();
            }
            if (!Schema::hasColumn('student_fees', 'billing_date')) {
                $table->date('billing_date')->nullable()->after('paid_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropColumn(['fee_name_id', 'billing_period_id', 'billing_date']);
        });
    }
};