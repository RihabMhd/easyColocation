<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Http\Requests\UpdateColocationRequest;
use App\Http\Requests\StoreColocationRequest;

class ColocationController extends Controller
{
    public function index()
    {
        $colocations = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'active')->get();

        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(StoreColocationRequest $request)
    {
        // Block if user already has an active colocation
        $hasActive = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'active')->exists();

        if ($hasActive) {
            return redirect()->route('colocations.index')
                ->with('error', 'You already have an active colocation.');
        }

        $colocation = Colocation::create($request->validated());

        $colocation->memberships()->create([
            'user_id' => auth()->id(),
            'joined_at' => now(),
            'internal_role' => 'owner',
        ]);

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation created successfully.');
    }

    public function show(string $id)
    {
        $colocation = Colocation::with('memberships.user')->findOrFail($id);
        return view('colocations.show', compact('colocation'));
    }

    public function edit(string $id)
    {
        $colocation = Colocation::findOrFail($id);
        return view('colocations.edit', compact('colocation'));
    }

    public function update(UpdateColocationRequest $request, string $id)
    {
        $colocation = Colocation::findOrFail($id);
        $colocation->update($request->validated());

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation updated successfully.');
    }

    public function destroy(string $id)
    {
        $colocation = Colocation::findOrFail($id);
        $colocation->delete();

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation deleted successfully.');
    }

    public function transferOwnership(string $id, string $userId)
    {
        $colocation = Colocation::findOrFail($id);

        $ownerMembership = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->firstOrFail();

        $newOwnerMembership = $colocation->memberships()
            ->where('user_id', $userId)
            ->firstOrFail();

        $ownerMembership->update(['internal_role' => 'member']);
        $newOwnerMembership->update(['internal_role' => 'owner']);

        return redirect()->route('colocations.show', $id)
            ->with('success', 'Ownership transferred. You can now quit.');
    }

    public function quit(string $id)
    {
        $colocation = Colocation::findOrFail($id);

        $membership = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->firstOrFail();

      
        if ($membership->internal_role === 'owner') {
            return redirect()->route('colocations.show', $id)
                ->with('error', 'You must transfer ownership before quitting.');
        }

        
        $membership->delete();
        $colocation->update(['status' => 'cancelled']);

        return redirect()->route('colocations.historique')
            ->with('success', 'You have left the colocation. It has been cancelled.');
    }

    public function historique()
    {
        $colocations = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'cancelled')->get();

        return view('colocations.historique', compact('colocations'));
    }
}
