@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('user.colocations.show', $colocation->id) }}"
                class="transition hover:text-gray-700">{{ $colocation->name }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-medium text-gray-950 text-xs uppercase tracking-widest">New Expense</span>
        </nav>

        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-xl">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <h1 class="text-2xl font-extrabold text-gray-950 mb-8">Record an Expense</h1>

            <form action="{{ route('user.expenses.store', $colocation->id) }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Title</label>
                        <input type="text" name="title" placeholder="e.g., Weekly Groceries" required
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Amount (€)</label>
                            <input type="number" name="amount" step="0.01" required
                                class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                                class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Payer</label>
                        <div class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-600 text-sm">
                            {{ auth()->user()->name }} (You)
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                        <input list="category-options" name="category_name" placeholder="Search or create..." required
                            class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                        <datalist id="category-options">
                            @foreach ($categories as $category)
                                <option value="{{ $category->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition shadow-md">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
