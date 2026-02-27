@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-xl">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <header class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-950">My Colocations</h1>
            </div>
            <button onclick="openModal('createModal')"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow hover:bg-indigo-500">
                + New Colocation
            </button>
        </header>

        @if ($colocations->isEmpty())
            <div
                class="flex flex-col items-center justify-center p-12 bg-white border border-gray-200 border-dashed rounded-xl text-center">
                <h3 class="text-lg font-bold text-gray-950">No active colocation</h3>
                <p class="text-sm text-gray-500 mb-6">Create one to get started with shared expenses.</p>
                <button onclick="openModal('createModal')"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg">
                    Create first colocation
                </button>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach ($colocations as $colocation)
                    <div
                        class="relative bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:border-indigo-500 transition-all">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-950">{{ $colocation->name }}</h3>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-md bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                {{ ucfirst($colocation->status) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 line-clamp-2 mb-6">{{ $colocation->description }}</p>

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('user.colocations.show', $colocation->id) }}"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View</a>
                                @can('update', $colocation)
                                <button
                                    onclick="openEditModal({{ $colocation->id }}, '{{ addslashes($colocation->name) }}', '{{ addslashes($colocation->description) }}')"
                                    class="text-sm font-semibold text-gray-600 hover:text-gray-900">
                                    Edit
                                </button>

                                <a href="{{ route('user.colocations.show', $colocation->id) }}"
                                    class="text-sm font-semibold text-emerald-600 hover:text-emerald-500">
                                    Invite
                                </a>
                            @endcan

                            <div class="ml-auto text-xs text-gray-400">
                                👥 {{ $colocation->memberships->count() }} members
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- modals --}}
    <div id="modalBackdrop"
        class="fixed inset-0 z-[60] hidden bg-gray-950/60 backdrop-blur-sm flex items-center justify-center p-4">

        {{-- create --}}
        <div id="createModal" class="hidden w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
            <form action="{{ route('user.colocations.store') }}" method="POST" class="p-6">
                @csrf
                <h2 class="text-lg font-bold text-gray-950 mb-6">New Colocation</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Name</label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Description</label>
                        <textarea name="description" rows="3" required
                            class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Create</button>
                </div>
            </form>
        </div>

        {{-- edit --}}
        <div id="editModal" class="hidden w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
            <form id="editForm" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <h2 class="text-lg font-bold text-gray-950 mb-6">Edit Colocation</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Name</label>
                        <input type="text" id="editName" name="name" required
                            class="w-full rounded-lg border-gray-300">
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Description</label>
                        <textarea id="editDescription" name="description" rows="3" required class="w-full rounded-lg border-gray-300"></textarea>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Save
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
    </script>
@endsection
