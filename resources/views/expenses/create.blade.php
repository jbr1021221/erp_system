@extends('layouts.app')
@section('page-title', 'Add Expense')
@section('breadcrumb', 'Finance · Expenses · New')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
  <div>
    <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:22px;color:var(--text-primary);">Add Expense</h1>
    <p style="font-size:13px;color:var(--text-muted);margin-top:2px;">Record institutional costs or request funds</p>
  </div>
  <a href="{{ route('expenses.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:var(--bg-surface);border:1px solid var(--border-color);border-radius:10px;font-size:13px;color:var(--text-secondary);text-decoration:none;">
    ← Back
  </a>
</div>

<form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main Column (2/3) --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

      {{-- Details Card --}}
      <div class="card p-6 animate-in delay-1">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Expense Details</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <x-form-field name="title" label="Expense Title *" type="text" :value="old('title')" placeholder="e.g. Monthly Electricity Bill" required />
          </div>
          <x-form-field name="amount" label="Amount (৳) *" type="number" :value="old('amount')" placeholder="0.00" required />
          <x-form-field name="expense_date" label="Date of Expense *" type="date" :value="old('expense_date', date('Y-m-d'))" required />
          <x-form-field name="vendor_name" label="Vendor / Payee Name" type="text" :value="old('vendor_name')" placeholder="e.g. Dhaka Power Hub" />
          <x-form-field name="invoice_number" label="Invoice / Receipt #" type="text" :value="old('invoice_number')" placeholder="INV-00123" />
        </div>
      </div>

      {{-- Description Card --}}
      <div class="card p-6 animate-in delay-2">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent-info);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Description</h2>
        </div>
        <textarea name="description" rows="4" placeholder="Detailed explanation for this expense..." required
          style="width:100%;padding:14px;border:1px solid var(--border-color);border-radius:12px;font-size:14px;background:var(--bg-surface);color:var(--text-primary);outline:none;resize:vertical;font-family:'DM Sans',sans-serif;"
          onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--border-color)'">{{ old('description') }}</textarea>
      </div>

      {{-- Attachments Card --}}
      <div class="card p-6 animate-in delay-3">
        <div class="flex items-center gap-3 mb-5">
          <div style="width:4px;height:22px;background:var(--accent-gold);border-radius:2px;"></div>
          <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:16px;color:var(--text-primary);">Attachments (Receipt)</h2>
        </div>
        <div style="border:2px dashed var(--border-color);border-radius:16px;padding:32px;text-align:center;background:var(--bg-surface-2);cursor:pointer;"
             onclick="document.getElementById('attachmentInput').click()">
          <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--text-muted);margin:0 auto 12px;"><path d="M21.2 15c.7-1.2 1-2.5.7-3.9-.6-2-2.4-3.5-4.4-3.5h-1.2c-.7-3-3.2-5.2-6.2-5.6-3-.3-5.9 1.3-7.3 4-1.2 2.5-1 5.4.5 7.6M12 18V8m0 0l-3 3m3-3l3 3"/></svg>
          <div style="font-size:14px;font-weight:600;color:var(--text-primary);">Click to upload file</div>
          <p style="font-size:12px;color:var(--text-muted);margin-top:4px;">PDF, PNG, JPG (max 5MB)</p>
          <input type="file" id="attachmentInput" name="attachment" class="hidden">
        </div>
      </div>

    </div>

    {{-- Right Sidebar (1/3) --}}
    <div class="flex flex-col gap-5">

      {{-- Category Selection --}}
      <div class="card p-6 animate-in delay-1">
        <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;color:var(--text-primary);margin-bottom:16px;">Category</h3>
        <input type="hidden" name="category" id="categoryInput" value="{{ old('category', 'Utilities') }}">
        <div class="grid grid-cols-2 gap-3">
          @foreach([
            ['name'=>'Utilities','icon'=>'M13 2L3 14h9l-1 8 10-12h-9l1-8z','color'=>'var(--accent-info)'],
            ['name'=>'Salaries','icon'=>'M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6','color'=>'var(--accent-green)'],
            ['name'=>'Supplies','icon'=>'M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34','color'=>'var(--accent-gold)'],
            ['name'=>'Maintenance','icon'=>'M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z','color'=>'var(--accent)'],
          ] as $cat)
          <div class="cat-btn" data-val="{{ $cat['name'] }}"
               style="padding:16px;border-radius:12px;text-align:center;cursor:pointer;border:2px solid {{ old('category', 'Utilities') == $cat['name'] ? 'var(--accent)' : 'var(--border-color)' }};background:var(--bg-surface);transition:all 0.2s;"
               onclick="document.getElementById('categoryInput').value='{{ $cat['name'] }}';document.querySelectorAll('.cat-btn').forEach(b=>b.style.borderColor='var(--border-color)');this.style.borderColor='var(--accent)'">
            <svg width="24" height="24" fill="none" stroke="{{ $cat['color'] }}" stroke-width="1.8" viewBox="0 0 24 24" style="margin:0 auto 8px;"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $cat['icon'] }}"/></svg>
            <div style="font-size:12px;font-weight:600;color:var(--text-primary);">{{ $cat['name'] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="card p-5 animate-in delay-2">
        <button type="submit" name="status" value="pending" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl py-3 px-4 font-bold text-[15px] transition-colors mb-3">
          Submit for Approval
        </button>
        <button type="submit" name="status" value="draft"
          style="width:100%;height:44px;background:var(--bg-surface-2);color:var(--text-secondary);border:1px solid var(--border-color);border-radius:12px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;margin-bottom:10px;"
          onmouseover="this.style.background='var(--border-color)'" onmouseout="this.style.background='var(--bg-surface-2)'">
          Save as Draft
        </button>
      </div>

    </div>
  </div>
</form>

@endsection
