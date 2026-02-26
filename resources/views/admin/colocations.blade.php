@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold tracking-tight text-gray-950">All Colocations</h1>
        </header>

        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($colocations as $colocation)
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="text-lg font-bold text-gray-950">{{ $colocation->name }}</h3>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">{{ $colocation->description }}</p>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2">Members</p>
                        <ul class="space-y-2">
                            @foreach($colocation->users as $member)
                                <li class="text-sm flex items-center justify-between text-gray-700">
                                    <span>{{ $member->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $member->email }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection