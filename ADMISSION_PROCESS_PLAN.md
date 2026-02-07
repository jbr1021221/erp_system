# Multi-Step Student Admission Process

## Flow Overview

### Step 1: Basic Student Information
**Route**: `/students/create`
**View**: `students/create.blade.php`
- Student personal details
- Academic information
- Guardian information
- **Action**: Submit → Store basic info → Redirect to Step 2

### Step 2: Fee Selection & Discount
**Route**: `/students/{student}/fees`
**View**: `students/select-fees.blade.php`
- Display all fees for selected class
- Checkboxes to select which fees apply to this student
- For each fee:
  - Discount percentage OR fixed amount
  - Mark as "Permanent Discount" or "One-time (Admission)"
- **Action**: Submit → Save fee selections → Redirect to Step 3

### Step 3: Partial Admission Fee Payment
**Route**: `/students/{student}/admission-payment`
**View**: `students/admission-payment.blade.php`
- Show selected fees with discounts applied
- Allow partial payment for admission fee only
- Remaining fees shown as "To be paid later"
- **Action**: Submit → Record payment → Redirect to Step 4

### Step 4: Admission Form Preview
**Route**: `/students/{student}/admission-preview`
**View**: `students/admission-preview.blade.php`
- Show filled admission form (A4 page)
- All student details populated
- **Action**: Confirm → Redirect to Step 5

### Step 5: Print Receipt & Admission Form
**Route**: `/students/{student}/admission-complete`
**View**: `students/admission-complete.blade.php`
- Split screen:
  - Left: Horizontal payment receipt
  - Right: A4 admission form
- Print button for both documents
- **Action**: Print → Done

## Database Changes Needed

### 1. Student Fee Assignments Table
```sql
CREATE TABLE student_fee_assignments (
    id BIGINT PRIMARY KEY,
    student_id BIGINT,
    fee_structure_id BIGINT,
    discount_type ENUM('percentage', 'fixed'),
    discount_value DECIMAL(10,2),
    is_permanent BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2. Payments Table (Already exists, may need modifications)
- Add `is_admission_payment` BOOLEAN field
- Add `partial_amount` DECIMAL field

## Implementation Plan

1. ✅ Create migration for student_fee_assignments
2. ✅ Create StudentFeeAssignment model
3. ✅ Update StudentController with new methods
4. ✅ Create view: select-fees.blade.php
5. ✅ Create view: admission-payment.blade.php
6. ✅ Create view: admission-preview.blade.php
7. ✅ Create view: admission-complete.blade.php
8. ✅ Create horizontal receipt view
9. ✅ Add routes for all steps
10. ✅ Update student creation to redirect to fee selection

## Files to Create/Modify

### New Files:
- `database/migrations/xxxx_create_student_fee_assignments_table.php`
- `app/Models/StudentFeeAssignment.php`
- `resources/views/students/select-fees.blade.php`
- `resources/views/students/admission-payment.blade.php`
- `resources/views/students/admission-preview.blade.php`
- `resources/views/students/admission-complete.blade.php`
- `resources/views/students/receipt-horizontal.blade.php`

### Modified Files:
- `app/Http/Controllers/Admin/StudentController.php`
- `routes/web.php`
- Possibly `database/migrations/xxxx_create_payments_table.php`

## Next Steps

Starting implementation...
