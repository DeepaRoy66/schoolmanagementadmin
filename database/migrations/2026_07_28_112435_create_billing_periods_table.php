<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name');       // e.g. "Baisakh"
            $table->string('code');       // e.g. "1"
            $table->integer('hierarchy'); // order
            $table->decimal('quantity', 8, 2)->default(1.00);
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['school_id', 'hierarchy']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_periods');
    }
};