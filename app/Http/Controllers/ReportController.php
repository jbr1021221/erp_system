<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\Classes;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function students(Request $request)
    {
        $query = Student::with(['class', 'section']);

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('first_name')->get();
        $classes  = Classes::where('is_active', true)->orderBy('name')->get();

        $total    = $students->count();
        $active   = $students->where('status', 'active')->count();
        $inactive = $students->where('status', 'inactive')->count();
        $other    = $total - $active - $inactive;

        return view('reports.students', compact('students', 'classes', 'total', 'active', 'inactive', 'other'));
    }

    public function payments(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to',   now()->format('Y-m-d'));

        $query = Payment::with('student')
            ->whereBetween('payment_date', [$from, $to]);

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments  = $query->latest('payment_date')->get();
        $total     = $payments->sum('amount');
        $count     = $payments->count();
        $average   = $count > 0 ? round($total / $count) : 0;
        $methods   = $payments->groupBy('payment_method')->map->sum('amount');
        $topMethod = $methods->isEmpty()
            ? '—'
            : ucwords(str_replace('_', ' ', $methods->sortDesc()->keys()->first()));

        return view('reports.payments', compact(
            'payments', 'total', 'count', 'average', 'topMethod', 'from', 'to'
        ));
    }

    public function expenses(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to',   now()->format('Y-m-d'));

        $query = Expense::with(['category', 'approvedBy'])
            ->whereBetween('expense_date', [$from, $to]);

        if ($request->filled('category_id'))    $query->where('category_id',    $request->category_id);
        if ($request->filled('status'))         $query->where('status',         $request->status);
        if ($request->filled('payment_method')) $query->where('payment_method', $request->payment_method);

        $expenses    = $query->latest('expense_date')->get();
        $total       = $expenses->sum('amount');
        $pending     = $expenses->where('status', 'pending')->count();
        $byCategory  = $expenses
            ->groupBy(fn($e) => optional($e->category)->name ?? 'Uncategorised')
            ->map->sum('amount')
            ->sortDesc();
        $topCategory = $byCategory->isEmpty() ? '—' : $byCategory->keys()->first();

        $categories = collect();
        try {
            $categories = \App\Models\ExpenseCategory::orderBy('name')->get();
        } catch (\Exception $e) {}

        return view('reports.expenses', compact(
            'expenses', 'total', 'pending', 'byCategory', 'topCategory',
            'categories', 'from', 'to'
        ));
    }

    public function financial(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $monthlyIncome = Payment::selectRaw('MONTH(payment_date) as month, SUM(amount) as total')
            ->whereYear('payment_date', $year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyExpenses = collect();
        try {
            $monthlyExpenses = Expense::selectRaw('MONTH(expense_date) as month, SUM(amount) as total')
                ->whereYear('expense_date', $year)
                ->groupBy('month')
                ->pluck('total', 'month');
        } catch (\Exception $e) {}

        $totalIncome   = $monthlyIncome->sum();
        $totalExpenses = $monthlyExpenses->sum();
        $netBalance    = $totalIncome - $totalExpenses;
        $maxVal        = max($monthlyIncome->max(), $monthlyExpenses->max(), 1);
        $years         = range(date('Y'), date('Y') - 4);

        return view('reports.financial', compact(
            'monthlyIncome', 'monthlyExpenses', 'totalIncome', 'totalExpenses',
            'netBalance', 'maxVal', 'year', 'years'
        ));
    }

    public function fees(Request $request)
    {
        $academicYear = $request->get('academic_year', date('Y'));

        $stats = Payment::query()
            ->join('fee_structures', 'payments.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_structures.academic_year', $academicYear)
            ->select(
                'fee_structures.fee_type',
                DB::raw('count(payments.id) as payment_count'),
                DB::raw('sum(payments.amount) as total_collected')
            )
            ->groupBy('fee_structures.fee_type')
            ->get();

        $yearlyTotal = $stats->sum('total_collected');

        $years = DB::table('fee_structures')->distinct()->pluck('academic_year');
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('reports.fees', compact('stats', 'yearlyTotal', 'academicYear', 'years'));
    }
}