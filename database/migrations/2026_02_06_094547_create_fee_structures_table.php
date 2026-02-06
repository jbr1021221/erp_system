<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('fee_type'); // Tuition, Admission, Transport, etc.
            $table->decimal('amount', 10, 2);
            $table->enum('frequency', ['one_time', 'monthly', 'quarterly', 'yearly']);
            $table->string('academic_year');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};