@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold tracking-tight text-gray-950">User Management</h1>
        </header>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-950">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->is_banned)
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-md bg-red-50 text-red-700 ring-1 ring-red-600/20">Banned</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-md bg-green-50 text-green-700 ring-1 ring-green-600/20">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="text-sm font-semibold text-red-600 hover:text-red-500">
                                            {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">You (Admin)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
