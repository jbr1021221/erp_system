<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function students()
    {
        return view('reports.students');
    }

    public function payments()
    {
        return view('reports.payments');
    }

    public function expenses()
    {
        return view('reports.expenses');
    }

    public function financial()
    {
        return view('reports.financial');
    }

    public function fees(Request $request)
    {
        $academicYear = $request->get('academic_year', date('Y'));
        
        $stats = \App\Models\Payment::query()
            ->join('fee_structures', 'payments.fee_structure_id', '=', 'fee_structures.id')
            ->where('fee_structures.academic_year', $academicYear)
            ->select(
                'fee_structures.fee_type',
                \Illuminate\Support\Facades\DB::raw('count(payments.id) as payment_count'),
                \Illuminate\Support\Facades\DB::raw('sum(payments.amount) as total_collected')
            )
            ->groupBy('fee_structures.fee_type')
            ->get();

        $yearlyTotal = $stats->sum('total_collected');
        
        $years = \Illuminate\Support\Facades\DB::table('fee_structures')->distinct()->pluck('academic_year');
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('reports.fees', compact('stats', 'yearlyTotal', 'academicYear', 'years'));
    }
}
