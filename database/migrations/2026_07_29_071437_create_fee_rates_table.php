<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('fee_name_id')->constrained('fee_names')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('billing_period_id')->nullable()->constrained('billing_periods')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Euta fee name + class + period ko combination euta matra huna paucha
            $table->unique(['fee_name_id', 'class_id', 'billing_period_id'], 'fee_rate_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_rates');
    }
};