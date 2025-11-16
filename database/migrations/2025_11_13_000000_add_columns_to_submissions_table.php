<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('submissions', 'notes')) {
                $table->text('notes')->nullable()->after('status_id');
            }
            if (!Schema::hasColumn('submissions', 'teacher_notes')) {
                $table->text('teacher_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('submissions', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('teacher_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('submissions', 'teacher_notes')) {
                $table->dropColumn('teacher_notes');
            }
            if (Schema::hasColumn('submissions', 'submitted_at')) {
                $table->dropColumn('submitted_at');
            }
        });
    }
};
