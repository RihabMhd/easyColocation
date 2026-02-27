@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="border-b border-gray-200 pb-5">
        <h1 class="text-2xl font-bold tracking-tight text-gray-950">Historique</h1>
        <p class="text-sm text-gray-500 mt-1">Archive of your cancelled or completed colocations.</p>
    </div>

    <div class="space-y-4">
        {{-- show the list or an empty message if there are no colocations --}}
        @forelse($colocations as $colocation)
            <div class="flex items-center justify-between p-6 bg-white border border-gray-200 rounded-2xl opacity-75 hover:opacity-100 transition shadow-sm">
                <div>
                    <h3 class="font-bold text-gray-950">{{ $colocation->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $colocation->description }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 text-xs font-bold uppercase rounded-lg bg-gray-100 text-gray-600 border border-gray-200">
                        {{ $colocation->status }}
                    </span>
                    <a href="{{ route('user.colocations.show', $colocation->id) }}" class="text-sm font-semibold text-indigo-600">Review</a>
                </div>
            </div>
        @empty
            <div class="py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <p class="text-gray-400 font-medium">No history available.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection