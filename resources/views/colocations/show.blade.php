@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('colocations.index') }}" class="transition hover:text-gray-700">Colocations</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-medium text-gray-950">{{ $colocation->name }}</span>
    </nav>

    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-950">{{ $colocation->name }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                    {{ $colocation->status }}
                </span>
            </div>
            <p class="mt-2 text-gray-600 max-w-2xl">{{ $colocation->description }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="openEditModal({{ $colocation->id }}, '{{ addslashes($colocation->name) }}', '{{ addslashes($colocation->description) }}', '{{ $colocation->status }}')" 
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50">
                Edit Colocation
            </button>
            <a href="{{ route('colocations.depense', $colocation->id) }}" 
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-500">
                Manage Expenses
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Members</dt>
                    <dd class="text-3xl font-bold text-gray-950 mt-1">{{ $colocation->memberships->count() }}</dd>
                </div>
                <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                    <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Since</dt>
                    <dd class="text-3xl font-bold text-gray-950 mt-1">{{ $colocation->created_at->format('M Y') }}</dd>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-bold text-gray-950 uppercase tracking-widest">Members</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @foreach($colocation->memberships as $membership)
                        <li class="flex items-center justify-between p-4 px-6 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold uppercase text-sm">
                                    {{ substr($membership->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-950">{{ $membership->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $membership->user->email }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 border border-gray-200 rounded-md">
                                {{ ucfirst($membership->internal_role) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="space-y-6">
            <div class="p-6 bg-white border border-gray-200 rounded-2xl shadow-sm">
                <h3 class="text-sm font-bold text-gray-950 mb-4">Quick Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase">Created By</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $colocation->creator->name ?? 'System' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-md bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                {{ ucfirst($colocation->status) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<div id="modalBackdrop" class="fixed inset-0 z-[60] hidden bg-gray-950/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div id="editModal" class="hidden w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <form id="editForm" method="POST" class="p-6">
            @csrf @method('PUT')
            <h2 class="text-lg font-bold text-gray-950 mb-6">Edit Colocation</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Name</label>
                    <input type="text" id="editName" name="name" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Description</label>
                    <textarea id="editDescription" name="description" rows="3" required class="w-full rounded-lg border-gray-300"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                    <select id="editStatus" name="status" class="w-full rounded-lg border-gray-300">
                        <option value="active">Active</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg">Save Changes</button>
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
    function openEditModal(id, name, description, status) {
        document.getElementById('editForm').action = `/colocations/${id}`;
        document.getElementById('editName').value = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editStatus').value = status;
        openModal('editModal');
    }
</script>
@endsection