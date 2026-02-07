<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\FeeStructure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FeeStructureController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:fee-list', only: ['index', 'show']),
            new Middleware('permission:fee-structure-create', only: ['create', 'store']),
            new Middleware('permission:fee-structure-edit', only: ['edit', 'update']),
            new Middleware('permission:fee-structure-delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $classes = Classes::with('feeStructures')->get();
        return view('fee-structures.index', compact('classes'));
    }

    public function create()
    {
        $classes = Classes::all();
        $frequencies = FeeStructure::getFrequencies();
        return view('fee-structures.create', compact('classes', 'frequencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:one_time,monthly,quarterly,half_yearly,yearly',
            'academic_year' => 'required|string',
            'is_mandatory' => 'boolean',
        ]);

        FeeStructure::create($validated);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $classes = Classes::all();
        $frequencies = FeeStructure::getFrequencies();
        return view('fee-structures.edit', compact('feeStructure', 'classes', 'frequencies'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:one_time,monthly,quarterly,half_yearly,yearly',
            'academic_year' => 'required|string',
            'is_mandatory' => 'boolean',
        ]);

        $feeStructure->update($validated);

        return redirect()->route('fee-structures.index')->with('success', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('fee-structures.index')->with('success', 'Fee structure deleted successfully.');
    }
}
