@extends('layouts.app')

@section('title', 'Edit Expense - ERP System')

@section('subnav')
  <a href="{{ route('payments.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('payments.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Income / Fees</a>
  <a href="{{ route('fee-structures.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('fee-structures.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Fee Structure</a>
  <a href="{{ route('expenses.index') }}" class="text-sm px-4 h-[44px] flex items-center border-b-2 {{ request()->routeIs('expenses.*') ? 'border-emerald-600 text-emerald-600 font-medium' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Expenses</a>
@endsection


@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-start justify-between mb-8 pb-5 border-b border-slate-200 dark:border-slate-800">
    <div>
        <h1 class="text-xl font-semibold text-slate-800 dark:text-slate-100">Edit Expense Request
                </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Update expense details. Only pending expenses can be edited.
                </p>
    </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
            <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium text-slate-700">Expense Title <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" name="title" id="title" value="{{ old('title', $expense->title) }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="reference_no" class="block text-sm font-medium text-slate-700">Reference No</label>
                        <div class="mt-1">
                            <input type="text" name="reference_no" id="reference_no" value="{{ old('reference_no', $expense->reference_no) }}" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="category_id" class="block text-sm font-medium text-slate-700">Category <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="category_id" name="category_id" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="amount" class="block text-sm font-medium text-slate-700">Amount <span class="text-red-500">*</span></label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm">৳</span>
                            </div>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" required class="focus:ring-slate-500 focus:border-slate-500 block w-full pl-7 pr-12 sm:text-sm border-slate-300 rounded-md">
                        </div>
                         @error('amount') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="expense_date" class="block text-sm font-medium text-slate-700">Date <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                        </div>
                        @error('expense_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="payment_method" class="block text-sm font-medium text-slate-700">Payment Method <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="payment_method" name="payment_method" required class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="cash" {{ old('payment_method', $expense->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ old('payment_method', $expense->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cheque" {{ old('payment_method', $expense->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="online" {{ old('payment_method', $expense->payment_method) == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="card" {{ old('payment_method', $expense->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                            </select>
                        </div>
                         @error('payment_method') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="sm:col-span-6">
                        <label for="vendor_id" class="block text-sm font-medium text-slate-700">Vendor</label>
                        <div class="mt-1">
                            <select id="vendor_id" name="vendor_id" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border-slate-300 rounded-md">
                                <option value="">Select Vendor (Optional)</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $expense->vendor_id) == $vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border border-slate-300 rounded-md">{{ old('description', $expense->description) }}</textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="attachment" class="block text-sm font-medium text-slate-700">Attachment</label>
                        @if($expense->attachment)
                            <div class="mb-2">
                                <a href="{{ Storage::url($expense->attachment) }}" target="_blank" class="text-sm text-slate-800 hover:text-slate-800 underline">View current attachment</a>
                            </div>
                        @endif
                        <div class="mt-1">
                            <input type="file" name="attachment" id="attachment" class="shadow-sm focus:ring-slate-500 focus:border-slate-500 block w-full sm:text-sm border border-slate-300 rounded-md">
                            <p class="mt-1 text-xs text-slate-500">Upload to replace current attachment. Allowed types: jpeg, png, pdf.</p>
                        </div>
                        @error('attachment') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-200 flex justify-end">
                    <a href="{{ route('expenses.index') }}" class="bg-slate-50 py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-slate-900 hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500">
                        Update Expense
                    </button>
                </div>
            </form>
        </div>
    @endsection
