<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->date('expense_date');
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('restrict');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'card']);
            $table->string('payment_reference')->nullable();
            $table->text('description');
            $table->string('receipt_file')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};