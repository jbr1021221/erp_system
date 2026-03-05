<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * These columns come from the OLD project's student model and are
     * required during the data migration. They do NOT exist in the new
     * project schema yet, so they are intentionally nullable / nullable
     * with defaults so existing records are not affected.
     *
     * Field mapping from old → new:
     *   father_name    (old: father_name)   — direct carry-over
     *   mother_name    (old: mother_name)   — direct carry-over
     *   mother_mobile  (old: mother_mobile) — direct carry-over
     *   nid_file_path  (old: nid_file_path) — document upload path
     *   program_type   (old: program_type)  — e.g. "Morning", "Day"
     *   shift          (old: shift)         — e.g. "Morning", "Evening"
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Father / Mother / Guardian extras (old project fields)
            $table->string('father_name')->nullable()->after('guardian_relation');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('mother_mobile')->nullable()->after('mother_name');

            // Document / program extras (old project fields)
            $table->string('nid_file_path')->nullable()->after('photo');
            $table->string('program_type')->nullable()->after('nid_file_path');
            $table->string('shift')->nullable()->after('program_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'father_name',
                'mother_name',
                'mother_mobile',
                'nid_file_path',
                'program_type',
                'shift',
            ]);
        });
    }
};
