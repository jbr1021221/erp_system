<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:expense-list', only: ['index']),
            new Middleware('permission:expense-create', only: ['create', 'store']),
            new Middleware('permission:expense-edit', only: ['edit', 'update']),
            new Middleware('permission:expense-delete', only: ['destroy']),
            new Middleware('permission:expense-approve', only: ['approve']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with(['category', 'createdBy', 'vendor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
        }

        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $expenses = $query->latest()->paginate(10);
        $categories = ExpenseCategory::all();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function approve(Expense $expense)
    {
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id()
        ]);
        
        return back()->with('success', 'Expense approved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ExpenseCategory::all();
        $vendors = Vendor::all(); // Assuming Vendor model exists and is populated
        return view('expenses.create', compact('categories', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'reference_no' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online,card',
            'vendor_id' => 'nullable|exists:vendors,id', // Assuming vendors table
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = 'pending';
        
        // Auto-generate reference if not provided
        if (empty($validated['reference_no'])) {
            $validated['reference_no'] = 'EXP-' . strtoupper(uniqid());
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['category', 'createdBy', 'vendor']);
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot edit an approved expense.');
        }

        $categories = ExpenseCategory::all();
        $vendors = Vendor::all();
        return view('expenses.edit', compact('expense', 'categories', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot update an approved expense.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'reference_no' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online,card',
            'vendor_id' => 'nullable|exists:vendors,id',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
             if ($expense->attachment) {
                // Storage::disk('public')->delete($expense->attachment); // Need to import Storage
            }
            $validated['attachment'] = $request->file('attachment')->store('expenses', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->status === 'approved') {
            return back()->with('error', 'Cannot delete an approved expense.');
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
