<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedTinyInteger('billing_month')->nullable()->after('fee_structure_id');
            $table->string('billing_year', 4)->nullable()->after('billing_month');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['billing_month', 'billing_year']);
        });
    }
};
