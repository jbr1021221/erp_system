<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFeeAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'discount_type',
        'discount_value',
        'is_permanent',
        'notes',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'is_permanent' => 'boolean',
    ];

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the fee structure
     */
    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    /**
     * Calculate the final fee amount after discount
     */
    public function getFinalAmount()
    {
        $originalAmount = $this->feeStructure->amount;

        if ($this->discount_type === 'none') {
            return $originalAmount;
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $originalAmount - $this->discount_value);
        }

        if ($this->discount_type === 'percentage') {
            $discountAmount = ($originalAmount * $this->discount_value) / 100;
            return max(0, $originalAmount - $discountAmount);
        }

        return $originalAmount;
    }

    /**
     * Get the discount amount
     */
    public function getDiscountAmount()
    {
        $originalAmount = $this->feeStructure->amount;

        if ($this->discount_type === 'none') {
            return 0;
        }

        if ($this->discount_type === 'fixed') {
            return min($this->discount_value, $originalAmount);
        }

        if ($this->discount_type === 'percentage') {
            return ($originalAmount * $this->discount_value) / 100;
        }

        return 0;
    }
}
