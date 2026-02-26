@extends('layouts.app')

@section('content')
    @php
        $isOwner = $colocation
            ->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->exists();
        $memberCount = $colocation->memberships->count();
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('colocations.index') }}" class="transition hover:text-gray-700">Colocations</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-medium text-gray-950">{{ $colocation->name }}</span>
        </nav>

        {{-- Notifications --}}
        @if (session('success'))
            <div class="p-4 text-sm text-green-700 bg-green-50 rounded-xl border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 text-sm text-red-700 bg-red-50 rounded-xl border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        {{-- OWNER ONLY: Invite Section --}}
        @if ($isOwner)
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-950">Invite Roommate</h3>
                    <p class="text-sm text-gray-500">Send an invitation link via email</p>
                </div>

                <form action="{{ route('invitations.send', $colocation->id) }}" method="POST">
                    @csrf
                    <div class="flex flex-col sm:flex-row items-end gap-4">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email Address</label>
                            <input type="email" name="email" placeholder="roommate@example.com" required
                                class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 transition shadow-sm">
                            Send Invite
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-950">{{ $colocation->name }}</h1>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                        {{ $colocation->status }}
                    </span>
                </div>
                <p class="mt-2 text-gray-600 max-w-2xl">{{ $colocation->description }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if ($isOwner)
                    <button
                        onclick="openEditModal({{ $colocation->id }}, '{{ addslashes($colocation->name) }}', '{{ addslashes($colocation->description) }}')"
                        class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm">
                        Edit
                    </button>

                    @if ($memberCount === 1)
                        <form action="{{ route('colocations.destroy', $colocation->id) }}" method="POST"
                            onsubmit="return confirm('Archive this colocation?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50">
                                Cancel Colocation
                            </button>
                        </form>
                    @endif
                @endif

                <form action="{{ route('colocations.quit', $colocation->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to leave?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Quit
                    </button>
                </form>

                <a href="{{ route('expenses.index', $colocation->id) }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-500 shadow-sm">
                    Manage Expenses
                </a>
            </div>
        </div>

        {{-- Members List --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-950 uppercase tracking-widest">Members ({{ $memberCount }})</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($colocation->memberships as $membership)
                    <li class="flex items-center justify-between p-4 px-6 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold uppercase text-sm">
                                {{ substr($membership->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-950">
                                    {{ $membership->user->name }}
                                    @if ($membership->user_id === auth()->id())
                                        <span
                                            class="ml-1 text-[10px] bg-indigo-600 text-white px-1.5 py-0.5 rounded-full uppercase">You</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $membership->user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span
                                class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 rounded-md">
                                {{ ucfirst($membership->internal_role) }}
                            </span>

                            {{-- Menu Wrapper --}}
                            @if ($isOwner && $membership->user_id !== auth()->id())
                                <div class="relative">
                                    <button onclick="toggleMemberMenu(event, {{ $membership->id }})"
                                        class="p-2 hover:bg-gray-200 rounded-full transition duration-200 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                        </svg>
                                    </button>

                                    {{-- Actual Dropdown --}}
                                    <div id="dropdown-{{ $membership->id }}"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-xl z-[100] overflow-hidden">

                                        <form
                                            action="{{ route('colocations.transfer', [$colocation->id, $membership->user_id]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                                Make Owner
                                            </button>
                                        </form>

                                        <form
                                            action="{{ route('colocations.removeMember', [$colocation->id, $membership->user_id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure? You will inherit all their unpaid debts.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                Kick Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="modalBackdrop"
        class="fixed inset-0 z-[60] hidden bg-gray-950/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div id="editModal" class="hidden w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <h2 class="text-lg font-bold text-gray-950 mb-6">Edit Colocation</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                        <input type="text" id="editName" name="name" required
                            class="w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description</label>
                        <textarea id="editDescription" name="description" rows="3" required class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg shadow">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modalBackdrop').classList.remove('hidden');
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById('modalBackdrop').classList.add('hidden');
            document.getElementById(id).classList.add('hidden');
        }

        function openEditModal(id, name, description) {
            document.getElementById('editForm').action = `/colocations/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            openModal('editModal');
        }

        function toggleMemberMenu(event, id) {
            event.preventDefault();
            event.stopPropagation();

            const menu = document.getElementById(`dropdown-${id}`);

            // Close all other menus
            document.querySelectorAll('[id^="dropdown-"]').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });

            // Toggle current menu
            menu.classList.toggle('hidden');
        }

        // Close when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('[id^="dropdown-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        });
    </script>
@endsection
