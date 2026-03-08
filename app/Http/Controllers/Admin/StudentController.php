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
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class StudentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:student-list',   only: ['index']),
            new Middleware('permission:student-create', only: ['create', 'store']),
            new Middleware('permission:student-edit',   only: ['edit', 'update']),
            new Middleware('permission:student-delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        // FIX: eager-load 'class' (matches belongsTo relationship name in Student model)
        // and also try 'schoolClass' alias if you have it — here we normalise to 'class'
        $query = Student::with(['class', 'section']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name',  'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email',      'like', "%{$search}%")
                  ->orWhere('guardian_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // FIX: use paginate() so the view gets a LengthAwarePaginator with ->total(), ->links(), etc.
        $students = $query->latest()->paginate(20)->withQueryString();
        $classes  = Classes::where('is_active', true)->orderBy('name')->get();

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes  = Classes::where('is_active', true)->orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        return view('students.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'date_of_birth'    => 'required|date',
            'gender'           => 'required|in:male,female,other',
            'email'            => 'nullable|email|unique:students,email',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_name'    => 'required|string|max:255',
            'guardian_phone'   => 'required|string|max:20',
            'guardian_email'   => 'nullable|email',
            'guardian_relation'=> 'nullable|string|max:50',
            'class_id'         => 'required|exists:classes,id',
            'section_id'       => 'nullable|exists:sections,id',
            'enrollment_date'  => 'required|date',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create($validated);

        return redirect()->route('students.fee-payment', $student)
            ->with('success', 'Student information saved. Now configure fees and initial payment.');
    }

    public function show(Student $student)
    {
        $student->load(['class.feeStructures', 'section', 'payments' => fn($q) => $q->latest()]);

      $academicYear  = date('Y');
$feeStructures = $student->class->feeStructures ?? collect();
$ledger = [];

foreach ($feeStructures as $fee) {
    $status = [];
    $months = match ($fee->frequency) {
        'monthly'     => range(1, 12),
        'quarterly'   => [3, 6, 9, 12],
        'half_yearly' => [6, 12],
        default       => null,
    };

    // Get payments for this fee by fee_structure_id OR fee_type name
    $feePayments = $student->payments->filter(function($p) use ($fee) {
        return $p->fee_structure_id == $fee->id
            || strtolower(trim($p->fee_type)) == strtolower(trim($fee->fee_type));
    });

    if ($months) {
        // New payments with billing_month set
        $newPayments = $feePayments->whereNotNull('billing_month');
        // Migrated payments without billing_month
        $migratedPayments = $feePayments->whereNull('billing_month');
        
        // Count how many months are covered by migrated payments
        $migratedTotal = $migratedPayments->sum('amount');
        $migratedMonthsCovered = $migratedTotal > 0 
            ? (int) floor($migratedTotal / $fee->amount)
            : 0;

        foreach ($months as $idx => $m) {
            // Check new-style payment first
            $paid = $newPayments
                ->where('billing_month', $m)
                ->sum('amount');

            if ($paid > 0) {
                $status[$m] = $paid >= $fee->amount ? 'paid' : 'partial';
            } elseif ($idx < $migratedMonthsCovered) {
                // Mark early months as paid from migrated data
                $status[$m] = 'paid';
            } else {
                $status[$m] = 'due';
            }
        }
    } else {
        $paid = $feePayments->sum('amount');
        $status['one_time'] = $paid >= $fee->amount ? 'paid' : ($paid > 0 ? 'partial' : 'due');
    }

    $ledger[] = ['fee' => $fee, 'status' => $status];
}
        return view('students.show', compact('student', 'ledger', 'academicYear'));
    }

    public function edit(Student $student)
    {
        $classes  = Classes::where('is_active', true)->orderBy('name')->get();
        $sections = Section::where('class_id', $student->class_id)->get();
        return view('students.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'date_of_birth'    => 'required|date',
            'gender'           => 'required|in:male,female,other',
            'email'            => 'nullable|email|unique:students,email,' . $student->id,
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'guardian_name'    => 'required|string|max:255',
            'guardian_phone'   => 'required|string|max:20',
            'guardian_email'   => 'nullable|email',
            'guardian_relation'=> 'nullable|string|max:50',
            'class_id'         => 'required|exists:classes,id',
            'section_id'       => 'nullable|exists:sections,id',
            'status'           => 'required|in:active,inactive,graduated,transferred,suspended',
        ]);

        if ($request->hasFile('photo')) {
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
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    // ── Admission flow ────────────────────────────────────────────────────────

    public function admissionForm(Student $student)
    {
        $student->load('class');
        return view('students.admission-form', compact('student'));
    }

    public function showFeePayment(Student $student)
    {
        $student->load(['class.feeStructures', 'feeAssignments']);
        $feeStructures = $student->class->feeStructures ?? collect();
        return view('students.fee-payment', compact('student', 'feeStructures'));
    }

    public function storeFeePayment(Request $request, Student $student)
    {
        $validated = $request->validate([
            'fees'           => 'required|array|min:1',
            'fees.*'         => 'exists:fee_structures,id',
            'discount_type'  => 'array',
            'discount_value' => 'array',
            'is_permanent'   => 'array',
            // Payment fields
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $student, $validated) {
            // 1. Save Fee Assignments
            $student->feeAssignments()->delete();
            foreach ($validated['fees'] as $feeId) {
                StudentFeeAssignment::create([
                    'student_id'       => $student->id,
                    'fee_structure_id' => $feeId,
                    'discount_type'    => $request->input("discount_type.{$feeId}", 'none'),
                    'discount_value'   => $request->input("discount_value.{$feeId}", 0) ?: 0,
                    'is_permanent'     => $request->has("is_permanent.{$feeId}"),
                ]);
            }

            // 2. Save Payment (usually against the Admission Fee if selected)
            $admissionFeeId = \App\Models\FeeStructure::whereIn('id', $validated['fees'])
                ->where('fee_type', 'like', '%admission%')
                ->first()?->id;

            if ($validated['payment_amount'] > 0) {
                Payment::create([
                    'student_id'       => $student->id,
                    'amount'           => $validated['payment_amount'],
                    'payment_method'   => $validated['payment_method'],
                    'payment_date'     => $validated['payment_date'],
                    'billing_month'    => null,
                    'billing_year'     => date('Y'),
                    'fee_structure_id' => $admissionFeeId,
                    'fee_type'         => $admissionFeeId ? \App\Models\FeeStructure::find($admissionFeeId)->fee_type : 'Admission Fee',
                    'remarks'          => $validated['notes'] ?? 'Initial admission payment',
                    'status'           => 'completed',
                    'receipt_number'   => 'ADM-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'received_by'      => auth()->id(),
                ]);
            }
        });

        return redirect()->route('students.admission-preview', $student)
            ->with('success', 'Fees and payment recorded successfully.');
    }

    public function admissionPreview(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure', 'payments']);
        return view('students.admission-preview', compact('student'));
    }

    public function admissionComplete(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure', 'payments']);
        $payment = $student->payments()->latest()->first();
        return view('students.admission-complete', compact('student', 'payment'));
    }
}