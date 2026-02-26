@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('expenses.index', $colocation->id) }}" class="hover:text-gray-700">Expenses</a>
        <span>/</span>
        <span class="font-medium text-gray-950">{{ $expense->title }}</span>
    </nav>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-100 bg-gray-50/50">
            <h1 class="text-2xl font-extrabold text-gray-950">{{ $expense->title }}</h1>
            <p class="text-gray-500 text-sm">Total: <span class="font-bold text-gray-900">{{ number_format($expense->amount, 2) }} €</span></p>
        </div>

        <div class="p-8">
            <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6">Roommate Splits</h2>
            
            <div class="space-y-4">
                @foreach($expense->settlements as $settlement)
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase">
                                {{ substr($settlement->debtor->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $settlement->debtor->name }}</p>
                                <p class="text-xs text-gray-500">Owes {{ number_format($settlement->amount, 2) }} €</p>
                            </div>
                        </div>

                        <div>
                            @if($settlement->is_paid)
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Paid</span>
                            @else
                                {{-- Only show button to the person who is owed money --}}
                                @if($expense->user_id === auth()->id())
                                    <form action="{{ route('settlements.update', $settlement->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-500 transition">
                                            Mark as Paid
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Pending</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection