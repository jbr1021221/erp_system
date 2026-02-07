# Fee Structure - Discount & Partial Payment Feature

## Overview
Added discount and partial payment functionality to the FeeStructure model to support flexible fee management.

## Database Changes

### New Columns Added to `fee_structures` table:

1. **discount_percentage** (decimal 5,2)
   - Default: 0
   - Range: 0-100
   - Percentage discount to apply to the fee amount

2. **discount_amount** (decimal 10,2)
   - Default: 0
   - Fixed discount amount in currency
   - Takes priority over percentage discount

3. **allow_partial_payment** (boolean)
   - Default: false
   - Whether this fee allows partial payments

4. **minimum_partial_amount** (decimal 10,2)
   - Nullable
   - Minimum amount required for partial payment
   - If not set, defaults to 10% of discounted amount

## Model Methods

### Discount Methods

#### `getDiscountedAmount()`
Calculates the final amount after applying discounts.
- **Priority**: Fixed discount amount > Percentage discount
- **Returns**: Discounted amount (never negative)

```php
$fee = FeeStructure::find(1);
$discountedAmount = $fee->getDiscountedAmount();
```

#### `getDiscountValue()`
Gets the total discount value being applied.
- **Returns**: The discount amount

```php
$discountValue = $fee->getDiscountValue();
```

### Partial Payment Methods

#### `isPartialPaymentAllowed()`
Checks if partial payment is enabled for this fee.
- **Returns**: boolean

```php
if ($fee->isPartialPaymentAllowed()) {
    // Show partial payment option
}
```

#### `isValidPartialAmount($amount)`
Validates if a partial payment amount is acceptable.
- **Checks**:
  - Partial payment is enabled
  - Amount meets minimum requirement
  - Amount doesn't exceed total fee
- **Returns**: boolean

```php
if ($fee->isValidPartialAmount(5000)) {
    // Process partial payment
}
```

#### `getMinimumPartialAmount()`
Gets the minimum partial payment amount.
- **Returns**: Minimum amount (custom or 10% of discounted fee)

```php
$minAmount = $fee->getMinimumPartialAmount();
```

## Usage Examples

### Example 1: Fee with Percentage Discount
```php
$fee = new FeeStructure([
    'fee_type' => 'Tuition Fee',
    'amount' => 10000,
    'discount_percentage' => 15, // 15% discount
    'frequency' => 'monthly',
]);

$originalAmount = $fee->amount; // 10000
$discountedAmount = $fee->getDiscountedAmount(); // 8500
$discountValue = $fee->getDiscountValue(); // 1500
```

### Example 2: Fee with Fixed Discount
```php
$fee = new FeeStructure([
    'fee_type' => 'Admission Fee',
    'amount' => 50000,
    'discount_amount' => 5000, // Fixed 5000 discount
    'frequency' => 'one_time',
]);

$discountedAmount = $fee->getDiscountedAmount(); // 45000
```

### Example 3: Partial Payment
```php
$fee = new FeeStructure([
    'fee_type' => 'Annual Fee',
    'amount' => 30000,
    'allow_partial_payment' => true,
    'minimum_partial_amount' => 5000,
    'frequency' => 'yearly',
]);

// Check if partial payment is allowed
if ($fee->isPartialPaymentAllowed()) {
    $minAmount = $fee->getMinimumPartialAmount(); // 5000
    
    // Validate payment amount
    if ($fee->isValidPartialAmount(10000)) {
        // Process payment of 10000
    }
}
```

### Example 4: Combined Discount + Partial Payment
```php
$fee = new FeeStructure([
    'fee_type' => 'Transport Fee',
    'amount' => 20000,
    'discount_percentage' => 10, // 10% discount
    'allow_partial_payment' => true,
    'minimum_partial_amount' => 3000,
    'frequency' => 'quarterly',
]);

$discountedAmount = $fee->getDiscountedAmount(); // 18000 (after 10% discount)
$minPartial = $fee->getMinimumPartialAmount(); // 3000

// Student can pay minimum 3000 towards the 18000 fee
if ($fee->isValidPartialAmount(5000)) {
    // Process partial payment of 5000
}
```

## Migration File
Location: `database/migrations/2026_02_07_071518_add_discount_and_partial_payment_to_fee_structures_table.php`

## Model File
Location: `app/Models/FeeStructure.php`

## Next Steps

To fully integrate this feature, you may want to:

1. **Update Controllers**:
   - Add discount and partial payment fields to create/edit forms
   - Validate discount and partial payment inputs
   - Use `getDiscountedAmount()` when displaying fees

2. **Update Views**:
   - Add discount input fields (percentage or fixed amount)
   - Add partial payment toggle and minimum amount input
   - Display discounted amounts in fee lists
   - Show partial payment options in payment forms

3. **Update Payment Processing**:
   - Check `isPartialPaymentAllowed()` before allowing partial payments
   - Validate amounts using `isValidPartialAmount()`
   - Track remaining balance for partial payments

4. **Update Receipts**:
   - Show original amount, discount, and final amount
   - Display partial payment information
   - Show remaining balance if applicable

## Benefits

✅ **Flexible Discounts**: Support both percentage and fixed amount discounts
✅ **Partial Payments**: Allow students to pay fees in installments
✅ **Validation**: Built-in validation for partial payment amounts
✅ **Easy Integration**: Helper methods make it simple to use in controllers and views
✅ **Backward Compatible**: Existing fees work without changes (defaults to no discount, no partial payment)
