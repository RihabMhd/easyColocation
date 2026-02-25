@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-950">Expenses</h1>
            <p class="mt-1 text-sm text-gray-500">Track and manage shared costs for this colocation.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl shadow-sm hover:bg-indigo-500 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Expense
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Spent</dt>
            <dd class="text-3xl font-bold text-indigo-600 mt-2">{{ number_format($depenses->sum('amount'), 2) }} €</dd>
        </div>
        <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
            <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">Transactions</dt>
            <dd class="text-3xl font-bold text-gray-950 mt-2">{{ $depenses->count() }}</dd>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        @if($depenses->isEmpty())
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-950">No expenses yet</h3>
                <p class="text-gray-500">Get started by adding your first payment.</p>
            </div>
        @else
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($depenses as $depense)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-950">{{ $depense->name }}</div>
                                <div class="text-xs text-gray-400">{{ $depense->created_at->format('d M, Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $depense->user->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-mono font-bold text-gray-950">
                                {{ number_format($depense->amount, 2) }} €
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection