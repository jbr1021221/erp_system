<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Section;
use App\Models\StudentFeeAssignment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:student-list', only: ['index']),
            new Middleware('permission:student-create', only: ['create', 'store']),
            new Middleware('permission:student-edit', only: ['edit', 'update']),
            new Middleware('permission:student-delete', only: ['destroy']),
        ];
    }


    public function index(Request $request)
    {
        $query = Student::with(['class', 'section']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id != '') {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $students = $query->latest()->get();
        $classes = Classes::where('is_active', true)->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = Classes::where('is_active', true)->get();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'nullable|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email',
            'guardian_relation' => 'nullable|string|max:50',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'enrollment_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create($validated);

        // Redirect to fee selection page (Step 2 of admission process)
        return redirect()->route('students.select-fees', $student)
            ->with('success', 'Student basic information saved. Please select applicable fees.');
    }

    public function show(Student $student)
    {
        $student->load(['class.feeStructures', 'section', 'payments' => function ($query) {
             $query->latest();
        }]);

        $academicYear = date('Y'); // Or fetch from settings
        $feeStructures = $student->class->feeStructures ?? collect();
        
        $ledger = [];
        foreach ($feeStructures as $fee) {
            $status = [];
            
            if ($fee->frequency == 'monthly') {
                for ($m = 1; $m <= 12; $m++) {
                    $paid = $student->payments
                        ->where('fee_structure_id', $fee->id)
                        ->where('billing_month', $m)
                        ->where('billing_year', $academicYear)
                        ->sum('amount');
                    $status[$m] = $paid >= $fee->amount ? 'paid' : ($paid > 0 ? 'partial' : 'due');
                }
            } else if ($fee->frequency == 'quarterly') {
                foreach ([3, 6, 9, 12] as $m) {
                    $paid = $student->payments
                        ->where('fee_structure_id', $fee->id)
                        ->where('billing_month', $m)
                        ->where('billing_year', $academicYear)
                        ->sum('amount');
                    $status[$m] = $paid >= $fee->amount ? 'paid' : ($paid > 0 ? 'partial' : 'due');
                }
            } else if ($fee->frequency == 'half_yearly') {
                foreach ([6, 12] as $m) {
                    $paid = $student->payments
                        ->where('fee_structure_id', $fee->id)
                        ->where('billing_month', $m)
                        ->where('billing_year', $academicYear)
                        ->sum('amount');
                    $status[$m] = $paid >= $fee->amount ? 'paid' : ($paid > 0 ? 'partial' : 'due');
                }
            } else {
                $paid = $student->payments
                    ->where('fee_structure_id', $fee->id)
                    ->where('billing_year', $academicYear)
                    ->sum('amount');
                $status['one_time'] = $paid >= $fee->amount ? 'paid' : ($paid > 0 ? 'partial' : 'due');
            }
            
            $ledger[] = [
                'fee' => $fee,
                'status' => $status
            ];
        }

        return view('students.show', compact('student', 'ledger', 'academicYear'));
    }

    public function edit(Student $student)
    {
        $classes = Classes::where('is_active', true)->get();
        $sections = Section::where('class_id', $student->class_id)->get();
        return view('students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_name' => 'required|string|max:255',
            'guardian_phone' => 'required|string|max:20',
            'guardian_email' => 'nullable|email',
            'guardian_relation' => 'nullable|string|max:50',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:active,inactive,graduated,transferred,suspended',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Delete photo
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Display the admission form for a student
     */
    public function admissionForm(Student $student)
    {
        $student->load('class');
        return view('students.admission-form', compact('student'));
    }

    /**
     * Step 2: Show fee selection page
     */
    public function selectFees(Student $student)
    {
        $student->load(['class.feeStructures', 'feeAssignments']);
        $feeStructures = $student->class->feeStructures ?? collect();
        
        return view('students.select-fees', compact('student', 'feeStructures'));
    }

    /**
     * Step 2: Store selected fees with discounts
     */
    public function storeFees(Request $request, Student $student)
    {
        $validated = $request->validate([
            'fees' => 'required|array|min:1',
            'fees.*' => 'exists:fee_structures,id',
            'discount_type' => 'array',
            'discount_type.*' => 'in:none,percentage,fixed',
            'discount_value' => 'array',
            'discount_value.*' => 'nullable|numeric|min:0',
            'is_permanent' => 'array',
        ]);

        // Delete existing fee assignments
        $student->feeAssignments()->delete();

        // Create new fee assignments
        foreach ($validated['fees'] as $feeId) {
            $discountType = $request->input("discount_type.{$feeId}", 'none');
            $discountValue = $request->input("discount_value.{$feeId}", 0);
            $isPermanent = $request->has("is_permanent.{$feeId}");

            StudentFeeAssignment::create([
                'student_id' => $student->id,
                'fee_structure_id' => $feeId,
                'discount_type' => $discountType,
                'discount_value' => $discountValue ?: 0,
                'is_permanent' => $isPermanent,
            ]);
        }

        return redirect()->route('students.admission-payment', $student)
            ->with('success', 'Fees selected successfully. Please process admission payment.');
    }

    /**
     * Step 3: Show admission payment page
     */
    public function admissionPayment(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure']);
        
        // Get admission fee assignment
        $admissionFee = $student->feeAssignments()
            ->whereHas('feeStructure', function($query) {
                $query->where('fee_type', 'like', '%admission%')
                      ->orWhere('fee_type', 'like', '%Admission%');
            })
            ->with('feeStructure')
            ->first();

        $otherFees = $student->feeAssignments()
            ->whereHas('feeStructure', function($query) {
                $query->where('fee_type', 'not like', '%admission%')
                      ->where('fee_type', 'not like', '%Admission%');
            })
            ->with('feeStructure')
            ->get();

        return view('students.admission-payment', compact('student', 'admissionFee', 'otherFees'));
    }

    /**
     * Step 3: Store admission payment
     */
    public function storeAdmissionPayment(Request $request, Student $student)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Get fee structure to get the fee type
        $feeStructure = \App\Models\FeeStructure::find($request->input('fee_structure_id'));

        // Create payment record
        Payment::create([
            'student_id' => $student->id,
            'amount' => $validated['payment_amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'billing_month' => null,
            'billing_year' => date('Y'),
            'fee_structure_id' => $request->input('fee_structure_id'),
            'fee_type' => $feeStructure ? $feeStructure->fee_type : 'Admission Fee',
            'remarks' => $validated['notes'] ?? 'Admission payment',
            'status' => 'completed',
            'receipt_number' => 'ADM-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'received_by' => auth()->id(),
        ]);

        return redirect()->route('students.admission-preview', $student)
            ->with('success', 'Payment recorded successfully. Please review admission form.');
    }

    /**
     * Step 4: Show admission form preview
     */
    public function admissionPreview(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure', 'payments']);
        return view('students.admission-preview', compact('student'));
    }

    /**
     * Step 5: Show completion page with receipt and admission form
     */
    public function admissionComplete(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure', 'payments']);
        
        // Get the latest admission payment
        $payment = $student->payments()->latest()->first();
        
        return view('students.admission-complete', compact('student', 'payment'));
    }
}