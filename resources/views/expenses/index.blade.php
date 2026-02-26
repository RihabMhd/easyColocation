@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Breadcrumbs --}}
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
        {{-- Add this above your Expense Table --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Money Owed to You --}}
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
                        <p class="text-xs text-gray-400 italic">No one owes you money right now.</p>
                    @endforelse
                </div>
            </div>

            {{-- Money You Owe --}}
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

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Title</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Payer</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($expenses as $expense)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-950">{{ $expense->title }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold bg-indigo-50 text-indigo-700 rounded-md ring-1 ring-inset ring-indigo-700/10">
                                        {{ $expense->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $expense->payer->name }}</td>
                                <td class="px-6 py-4 text-sm font-extrabold text-gray-950">
                                    {{ number_format($expense->amount, 2) }} €</td>
                                <td class="px-6 py-4 text-right flex justify-end gap-3">
                                    {{-- Show Edit only to the owner --}}
                                    @if ($expense->user_id === auth()->id())
                                        <a href="{{ route('expenses.edit', [$colocation->id, $expense->id]) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">Edit</a>
                                    @else
                                        {{-- Check if the current user has an unpaid settlement for this expense --}}
                                        @php
                                            $userSettlement = $expense
                                                ->settlements()
                                                ->where('debtor_id', auth()->id())
                                                ->where('is_paid', false)
                                                ->first();
                                        @endphp

                                        @if ($userSettlement)
                                            <form action="{{ route('settlements.update', $userSettlement->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-900 font-bold text-sm">
                                                    Mark as Paid
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Settled</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
@endsection
