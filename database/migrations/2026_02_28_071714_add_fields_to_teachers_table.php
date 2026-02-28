<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds optional subject, department, and qualification columns to teachers table.
 * Run with: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Only add columns if they don't already exist
            if (!Schema::hasColumn('teachers', 'subject')) {
                $table->string('subject')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('teachers', 'department')) {
                $table->string('department')->nullable()->after('subject');
            }
            if (!Schema::hasColumn('teachers', 'qualification')) {
                $table->string('qualification')->nullable()->after('department');
            }
            if (!Schema::hasColumn('teachers', 'status')) {
                // Add 'status' string column as alternative to 'is_active' boolean
                $table->string('status')->nullable()->default('active')->after('qualification');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $columns = ['subject', 'department', 'qualification', 'status'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('teachers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};