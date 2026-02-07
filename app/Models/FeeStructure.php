<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'allow_partial_payment' => 'boolean',
        'minimum_partial_amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public static function getFrequencies()
    {
        return [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half Yearly',
            'yearly' => 'Yearly',
            'one_time' => 'One Time',
        ];
    }

    /**
     * Calculate the discounted amount
     * Priority: Fixed discount amount > Percentage discount
     */
    public function getDiscountedAmount()
    {
        $originalAmount = $this->amount;
        
        // If fixed discount amount is set, use it
        if ($this->discount_amount > 0) {
            return max(0, $originalAmount - $this->discount_amount);
        }
        
        // Otherwise, apply percentage discount
        if ($this->discount_percentage > 0) {
            $discountAmount = ($originalAmount * $this->discount_percentage) / 100;
            return max(0, $originalAmount - $discountAmount);
        }
        
        // No discount
        return $originalAmount;
    }

    /**
     * Get the total discount amount
     */
    public function getDiscountValue()
    {
        if ($this->discount_amount > 0) {
            return $this->discount_amount;
        }
        
        if ($this->discount_percentage > 0) {
            return ($this->amount * $this->discount_percentage) / 100;
        }
        
        return 0;
    }

    /**
     * Check if partial payment is allowed
     */
    public function isPartialPaymentAllowed()
    {
        return $this->allow_partial_payment;
    }

    /**
     * Validate partial payment amount
     */
    public function isValidPartialAmount($amount)
    {
        if (!$this->allow_partial_payment) {
            return false;
        }

        // Check if amount meets minimum requirement
        if ($this->minimum_partial_amount && $amount < $this->minimum_partial_amount) {
            return false;
        }

        // Check if amount doesn't exceed total fee
        $maxAmount = $this->getDiscountedAmount();
        if ($amount > $maxAmount) {
            return false;
        }

        return true;
    }

    /**
     * Get minimum partial payment amount or default to 10% of fee
     */
    public function getMinimumPartialAmount()
    {
        if ($this->minimum_partial_amount) {
            return $this->minimum_partial_amount;
        }

        // Default to 10% of discounted amount
        return $this->getDiscountedAmount() * 0.1;
    }
}
