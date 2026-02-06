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
}
