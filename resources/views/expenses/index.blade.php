@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">

        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('colocations.index') }}" class="transition hover:text-gray-700">Colocations</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('colocations.show', $colocation->id) }}"
                class="transition hover:text-gray-700">{{ $colocation->name }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-medium text-gray-950">Expense History</span>
        </nav>

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-950">Financial History</h1>
                <p class="mt-2 text-gray-600">Track all shared costs for this house.</p>
            </div>
            <a href="{{ route('expenses.create', $colocation->id) }}"
                class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-500 transition shadow-sm">
                + Add New Expense
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                <h3 class="text-xs font-bold text-green-600 uppercase tracking-widest mb-4">Owed to You</h3>
                <div class="space-y-3">
                    @php
                        $credits = \App\Models\Settlement::where('creditor_id', auth()->id())
                            ->where('colocation_id', $colocation->id)
                            ->where('is_paid', false)
                            ->get();
                    @endphp
                    @forelse($credits as $credit)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $credit->debtor->name }} owes you</span>
                            <span class="text-sm font-bold text-gray-950">+{{ number_format($credit->amount, 2) }} €</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">No one owes you money.</p>
                    @endforelse
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                <h3 class="text-xs font-bold text-red-600 uppercase tracking-widest mb-4">Your Debts</h3>
                <div class="space-y-3">
                    @php
                        $debts = \App\Models\Settlement::where('debtor_id', auth()->id())
                            ->where('colocation_id', $colocation->id)
                            ->where('is_paid', false)
                            ->get();
                    @endphp
                    @forelse($debts as $debt)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">You owe {{ $debt->creditor->name }}</span>
                            <span class="text-sm font-bold text-red-600">-{{ number_format($debt->amount, 2) }} €</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">You are all caught up!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white p-4 border border-gray-200 rounded-2xl shadow-sm">
            <form action="{{ route('expenses.index', $colocation->id) }}" method="GET"
                class="flex flex-wrap items-center gap-4">

                <div class="flex flex-col gap-1 min-w-[150px]">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}"
                        class="block w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex flex-col gap-1 min-w-[180px]">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Category</label>
                    <select name="category_id"
                        class="block w-full rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-5">
                    <button type="submit"
                        class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition shadow-sm">
                        Filter
                    </button>

                    @if (request()->anyFilled(['month', 'category_id']))
                        <a href="{{ route('expenses.index', $colocation->id) }}"
                            class="px-5 py-2.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-200 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Payer</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($expenses as $expense)
                        <tr>
                            <td class="px-6 py-4 text-sm">{{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm font-bold">
                                <a href="{{ route('expenses.show', [$colocation->id, $expense->id]) }}"
                                    class="text-indigo-600 hover:underline">
                                    {{ $expense->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $expense->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $expense->payer->name }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-950">{{ number_format($expense->amount, 2) }}
                                €</td>
                            <td class="px-6 py-4 text-right">
                                {{-- Logic: Only the person who created the expense can see the 'Edit' button --}}
                                @if ($expense->user_id === auth()->id())
                                    <a href="{{ route('expenses.edit', [$colocation->id, $expense->id]) }}"
                                        class="inline-flex items-center px-3 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-lg transition">
                                        Edit
                                    </a>
                                @else
                                    <span class="text-gray-300 text-xs italic">View Only</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
