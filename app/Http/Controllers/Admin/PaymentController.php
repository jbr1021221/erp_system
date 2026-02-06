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
        if ($request->has('student_id')) {
            $student = Student::find($request->student_id);
        }
        return view('payments.create', compact('student'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,online,cheque,card',
            'fee_type' => 'required|string',
            'transaction_reference' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        // Generate Receipt Number
        $validated['receipt_number'] = 'REC-' . strtoupper(uniqid());
        $validated['received_by'] = auth()->id();
        $validated['status'] = 'completed';

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
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
}
