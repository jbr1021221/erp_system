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
        $classes = Classes::where('is_active', true)->orderBy('name')->get();
        return view('students.create', compact('classes'));
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

        return redirect()->route('students.select-fees', $student)
            ->with('success', 'Student information saved. Please select applicable fees.');
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

            if ($months) {
                foreach ($months as $m) {
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

    public function selectFees(Student $student)
    {
        $student->load(['class.feeStructures', 'feeAssignments']);
        $feeStructures = $student->class->feeStructures ?? collect();
        return view('students.select-fees', compact('student', 'feeStructures'));
    }

    public function storeFees(Request $request, Student $student)
    {
        $validated = $request->validate([
            'fees'          => 'required|array|min:1',
            'fees.*'        => 'exists:fee_structures,id',
            'discount_type' => 'array',
            'discount_type.*' => 'in:none,percentage,fixed',
            'discount_value'  => 'array',
            'discount_value.*'=> 'nullable|numeric|min:0',
            'is_permanent'    => 'array',
        ]);

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

        return redirect()->route('students.admission-payment', $student)
            ->with('success', 'Fees selected. Please process admission payment.');
    }

    public function admissionPayment(Student $student)
    {
        $student->load(['class', 'feeAssignments.feeStructure']);

        $admissionFee = $student->feeAssignments()
            ->whereHas('feeStructure', fn($q) => $q->where('fee_type', 'like', '%admission%'))
            ->with('feeStructure')
            ->first();

        $otherFees = $student->feeAssignments()
            ->whereHas('feeStructure', fn($q) => $q->where('fee_type', 'not like', '%admission%'))
            ->with('feeStructure')
            ->get();

        return view('students.admission-payment', compact('student', 'admissionFee', 'otherFees'));
    }

    public function storeAdmissionPayment(Request $request, Student $student)
    {
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'payment_date'   => 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $feeStructure = \App\Models\FeeStructure::find($request->input('fee_structure_id'));

        Payment::create([
            'student_id'       => $student->id,
            'amount'           => $validated['payment_amount'],
            'payment_method'   => $validated['payment_method'],
            'payment_date'     => $validated['payment_date'],
            'billing_month'    => null,
            'billing_year'     => date('Y'),
            'fee_structure_id' => $request->input('fee_structure_id'),
            'fee_type'         => $feeStructure?->fee_type ?? 'Admission Fee',
            'remarks'          => $validated['notes'] ?? 'Admission payment',
            'status'           => 'completed',
            'receipt_number'   => 'ADM-' . date('Ymd') . '-' . strtoupper(uniqid()),
            'received_by'      => auth()->id(),
        ]);

        return redirect()->route('students.admission-preview', $student)
            ->with('success', 'Payment recorded. Please review admission form.');
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