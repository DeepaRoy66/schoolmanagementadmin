<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            // Nullable on purpose: existing rows created before this migration
            // will have invoice_id = NULL until the backfill command links them.
            // Nothing is deleted or overwritten by this migration.
            $table->foreignId('invoice_id')->nullable()->after('created_by')
                ->constrained('invoices')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('student_fees', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_id');
        });
    }
};