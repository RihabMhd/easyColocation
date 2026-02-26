@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('expenses.index', $colocation->id) }}" class="transition hover:text-gray-700">Expenses</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-medium text-gray-950">Edit Expense</span>
        </nav>

        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h1 class="text-2xl font-extrabold text-gray-950 mb-8">Modify Entry</h1>

            <form action="{{ route('expenses.update', [$colocation->id, $expense->id]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1 text-indigo-700">Title</label>
                        <input type="text" name="title" value="{{ $expense->title }}" required 
                            class="w-full rounded-xl border-gray-300">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (€)</label>
                            <input type="number" name="amount" step="0.01" value="{{ $expense->amount }}" required 
                                class="w-full rounded-xl border-gray-300">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date</label>
                            <input type="date" name="date" value="{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}" required 
                                class="w-full rounded-xl border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                        <input list="categories" name="category_name" value="{{ $expense->category->name }}" required 
                            class="w-full rounded-xl border-gray-300">
                        <datalist id="categories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="pt-6 flex gap-3">
                    <a href="{{ route('expenses.index', $colocation->id) }}" class="flex-1 text-center px-6 py-3 text-sm font-medium text-gray-700 bg-gray-50 rounded-xl">Cancel</a>
                    <button type="submit" class="flex-[2] px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl shadow-md">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
@endsection