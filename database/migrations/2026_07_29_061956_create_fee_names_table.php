<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_names', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('fee_group_id')->constrained('fee_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->enum('fee_type', ['one_time', 'recurring'])->default('recurring');
            $table->boolean('is_taxable')->default(false);
            $table->boolean('discount_applicable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_names');
    }
};