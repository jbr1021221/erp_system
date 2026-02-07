<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payment-list', only: ['index']),
            new Middleware('permission:payment-create', only: ['create', 'store']),
            new Middleware('permission:payment-edit', only: ['edit', 'update']),
            new Middleware('permission:payment-delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('student');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('receipt_number', 'like', "%{$search}%")
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('student_id', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('payment_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('payment_date', '<=', $request->end_date);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->latest()->paginate(15);
        $totalAmount = $query->sum('amount'); // Calculate total for the current filtered set if needed, but pagination makes this tricky. 
        // Better to get total of filtered query before pagination if needed, but for now simple paginate is fine.
        
        // Let's actually pass a total stats of the current view if convenient, but let's stick to standard pagination primarily.

        return view('payments.index', compact('payments'));
    }

    public function studentPayments($studentId)
    {
        // Placeholder
        return view('payments.student', ['studentId' => $studentId]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $student = null;
        $payableFees = [];
        
        if ($request->has('student_id')) {
            $student = Student::with(['class.feeStructures', 'feeAssignments'])->find($request->student_id);
            
            if ($student) {
                $payableFees = $this->calculatePayableFees($student);
            }
        }
        
        $students = Student::active()->get();
        return view('payments.create', compact('student', 'students', 'payableFees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Decode JSON input if provided (for flexibility) or expect standard array input
        // Using standard array input 'items' from blade
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,online,cheque,card',
            'transaction_reference' => 'nullable|string',
            'remarks' => 'nullable|string',
            'billing_year' => 'nullable|string|size:4',
            
            'items' => 'required|array|min:1',
            'items.*.fee_structure_id' => 'required|exists:fee_structures,id',
            'items.*.fee_type' => 'required|string',
            'items.*.billing_month' => 'nullable|integer', // -1 for null
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        $receiptNumber = 'REC-' . date('Ymd') . '-' . strtoupper(uniqid());
        $receivedBy = \Illuminate\Support\Facades\Auth::id();
        $billingYear = $validated['billing_year'] ?? date('Y');

        foreach ($validated['items'] as $item) {
            // Handle placeholder for null month
            $billingMonth = ($item['billing_month'] == -1) ? null : $item['billing_month'];

            Payment::create([
                'student_id' => $validated['student_id'],
                'amount' => $item['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'fee_structure_id' => $item['fee_structure_id'],
                'fee_type' => $item['fee_type'],
                'billing_month' => $billingMonth,
                'billing_year' => $billingYear,
                'transaction_reference' => $validated['transaction_reference'],
                'remarks' => $validated['remarks'],
                'receipt_number' => $receiptNumber,
                'status' => 'completed',
                'received_by' => $receivedBy,
            ]);
        }

        return redirect()->route('payments.receipt', ['receipt_number' => $receiptNumber])->with('success', 'Payment completed successfully!');
    }

    /**
     * Display receipt for a payment transaction
     */
    public function receipt(Request $request)
    {
        $receiptNumber = $request->receipt_number;
        
        // Get all payment items with this receipt number
        $payments = Payment::with(['student.class', 'feeStructure', 'receivedBy'])
            ->where('receipt_number', $receiptNumber)
            ->get();
        
        if ($payments->isEmpty()) {
            return redirect()->route('payments.index')->with('error', 'Receipt not found.');
        }
        
        // Get the first payment for common details (student, date, method, etc.)
        $payment = $payments->first();
        
        // Calculate total amount
        $totalAmount = $payments->sum('amount');
        
        return view('payments.receipt', compact('payments', 'payment', 'totalAmount', 'receiptNumber'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Calculate payable fees grouped by Fee Structure with lists of unpaid periods.
     */
    private function calculatePayableFees(Student $student)
    {
        $fees = $student->class->feeStructures;
        $payments = isset($student->payments) ? $student->payments : $student->payments()->get();
        $payableFees = [];
        $currentYear = date('Y');

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        /* End-month mapping for periods */
        $quarters = [
            3 => 'Q1 (Jan-Mar)', 6 => 'Q2 (Apr-Jun)', 
            9 => 'Q3 (Jul-Sep)', 12 => 'Q4 (Oct-Dec)'
        ];
        $halfYears = [
            6 => 'H1 (Jan-Jun)', 12 => 'H2 (Jul-Dec)'
        ];

        foreach ($fees as $fee) {
            $assignment = $student->feeAssignments->where('fee_structure_id', $fee->id)->first();
            $amount = $assignment ? $assignment->getFinalAmount() : $fee->amount;
            
            // Local payments filter
            $structurePayments = $payments->where('fee_structure_id', $fee->id);

            $unpaidPeriods = [];

            if ($fee->frequency === 'monthly') {
                foreach ($months as $monthNum => $monthName) {
                    $isPaid = $structurePayments->where('billing_year', $currentYear)
                                                ->where('billing_month', $monthNum)
                                                ->isNotEmpty();
                    if (!$isPaid) {
                        $unpaidPeriods[] = ['value' => $monthNum, 'label' => $monthName];
                    }
                }
            } elseif ($fee->frequency === 'quarterly') {
                foreach ($quarters as $qMonth => $qName) {
                    $isPaid = $structurePayments->where('billing_year', $currentYear)
                                                ->where('billing_month', $qMonth)
                                                ->isNotEmpty();
                    if (!$isPaid) {
                        $unpaidPeriods[] = ['value' => $qMonth, 'label' => $qName];
                    }
                }
            } elseif ($fee->frequency === 'half_yearly') {
                foreach ($halfYears as $hMonth => $hName) {
                    $isPaid = $structurePayments->where('billing_year', $currentYear)
                                                ->where('billing_month', $hMonth)
                                                ->isNotEmpty();
                    if (!$isPaid) {
                        $unpaidPeriods[] = ['value' => $hMonth, 'label' => $hName];
                    }
                }
            } elseif ($fee->frequency === 'yearly') {
                $isPaid = $structurePayments->where('billing_year', $currentYear)->isNotEmpty();
                if (!$isPaid) {
                    $unpaidPeriods[] = ['value' => -1, 'label' => $currentYear]; // Use -1 as placeholder to pass validation
                }
            } elseif ($fee->frequency === 'one_time') {
                $isPaid = $structurePayments->isNotEmpty();
                if (!$isPaid) {
                    $unpaidPeriods[] = ['value' => -1, 'label' => 'One Time']; // Use -1 as placeholder
                }
            }

            // Only add if there are unpaid periods
            if (!empty($unpaidPeriods)) {
                $payableFees[] = [
                    'id' => $fee->id,
                    'fee_type' => $fee->fee_type,
                    'frequency' => $fee->frequency,
                    'unit_amount' => $amount,
                    'available_periods' => $unpaidPeriods
                ];
            }
        }
        
        return $payableFees;
    }

}
