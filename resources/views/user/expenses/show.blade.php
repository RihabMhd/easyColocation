@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('user.expenses.index', $colocation->id) }}" class="hover:text-gray-700">Expenses</a>
            <span>/</span>
            <span class="font-medium text-gray-950">{{ $expense->title }}</span>
        </nav>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-gray-50/50">
                <h1 class="text-2xl font-extrabold text-gray-950">{{ $expense->title }}</h1>
                <p class="text-gray-500 text-sm">Total: <span
                        class="font-bold text-gray-900">{{ number_format($expense->amount, 2) }} €</span></p>
            </div>

            <div class="p-8">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6">Roommate Splits</h2>

                <div class="space-y-4">
                    @foreach ($expense->settlements as $settlement)
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold uppercase">
                                    {{ substr($settlement->debtor->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-bold text-gray-900">{{ $settlement->debtor->name }}</p>


                                        <span title="Reputation Score"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $settlement->debtor->reputation_score >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            {{ $settlement->debtor->reputation_score ?? 0 }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500">Owes {{ number_format($settlement->amount, 2) }} €</p>
                                </div>
                            </div>

                            <div>
                                @if ($settlement->is_paid)
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Paid</span>
                                @else
                                    @can('settle', $expense)
                                        <form action="{{ route('user.settlements.update', $settlement->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-500 transition shadow-sm">
                                                Mark as Paid
                                            </button>
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Pending</span>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
