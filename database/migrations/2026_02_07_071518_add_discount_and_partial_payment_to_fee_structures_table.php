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
        Schema::table('fee_structures', function (Blueprint $table) {
            // Discount fields
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('amount')->comment('Discount percentage (0-100)');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage')->comment('Fixed discount amount');
            
            // Partial payment fields
            $table->boolean('allow_partial_payment')->default(false)->after('discount_amount')->comment('Allow partial payment for this fee');
            $table->decimal('minimum_partial_amount', 10, 2)->nullable()->after('allow_partial_payment')->comment('Minimum amount for partial payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropColumn([
                'discount_percentage',
                'discount_amount',
                'allow_partial_payment',
                'minimum_partial_amount'
            ]);
        });
    }
};
