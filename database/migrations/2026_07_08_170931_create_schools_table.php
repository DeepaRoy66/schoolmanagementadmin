<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('school_code')->unique()->nullable();
            $table->string('address')->nullable();
            $table->enum('license_status', ['active', 'expired', 'trial'])->default('trial');
            $table->date('license_start')->nullable();
            $table->date('license_expiry')->nullable();
            $table->date('trial_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};