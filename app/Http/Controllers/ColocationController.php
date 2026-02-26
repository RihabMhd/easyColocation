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

    public function store(StoreColocationRequest $request)
    {

        Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'active')->update(['status' => 'cancelled']);


        $colocation = Colocation::create(array_merge(
            $request->validated(),
            ['status' => 'active']
        ));

        $colocation->memberships()->create([
            'user_id' => auth()->id(),
            'joined_at' => now(),
            'internal_role' => 'owner',
        ]);

        return redirect()->route('colocations.index')
            ->with('success', 'New colocation created! Previous history has been archived.');
    }

    public function show(string $id)
    {
        $colocation = Colocation::with('memberships.user')->findOrFail($id);
        return view('colocations.show', compact('colocation'));
    }

    public function update(UpdateColocationRequest $request, string $id)
    {
        $colocation = Colocation::findOrFail($id);
        $colocation->update($request->validated());

        return redirect()->route('colocations.index')
            ->with('success', 'Colocation updated successfully.');
    }

  

    public function transferOwnership(string $id, string $userId)
    {
        $colocation = Colocation::findOrFail($id);

        
        $currentOwner = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->firstOrFail();

        $newOwnerMembership = $colocation->memberships()
            ->where('user_id', $userId)
            ->firstOrFail();

        $currentOwner->update(['internal_role' => 'member']);
        $newOwnerMembership->update(['internal_role' => 'owner']);

        return redirect()->back()->with('success', 'Ownership transferred successfully.');
    }

    public function kick(string $id, string $userId)
    {
        $colocation = Colocation::findOrFail($id);

       
        if (!$colocation->memberships()->where('user_id', auth()->id())->where('internal_role', 'owner')->exists()) {
            return redirect()->back()->with('error', 'Only the owner can remove members.');
        }

        $membership = $colocation->memberships()->where('user_id', $userId)->firstOrFail();

        if ($membership->internal_role === 'owner') {
            return redirect()->back()->with('error', 'You cannot kick the owner.');
        }

        $membership->delete();
        return redirect()->back()->with('success', 'Member has been removed.');
    }

    public function quit(string $id)
    {
        $colocation = Colocation::withCount('memberships')->findOrFail($id);
        $membership = $colocation->memberships()->where('user_id', auth()->id())->firstOrFail();

       
        if ($membership->internal_role === 'owner' && $colocation->memberships_count > 1) {
            return redirect()->back()->with('error', 'You must transfer ownership before leaving.');
        }

        $membership->delete();

        
        if ($colocation->memberships_count <= 1) {
            $colocation->update(['status' => 'cancelled']);
        }

        return redirect()->route('colocations.index')->with('success', 'You have left the colocation.');
    }

    public function destroy(string $id)
    {
        $colocation = Colocation::withCount('memberships')->findOrFail($id);

   
        $isOwner = $colocation->memberships()->where('user_id', auth()->id())->where('internal_role', 'owner')->exists();

        if (!$isOwner) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if ($colocation->memberships_count > 1) {
            return redirect()->back()->with('error', 'Cannot cancel a colocation that still has members.');
        }

        $colocation->update(['status' => 'cancelled']);
        return redirect()->route('colocations.index')->with('success', 'Colocation cancelled.');
    }

    public function historique()
    {
        $colocations = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'cancelled')->get();

        return view('colocations.historique', compact('colocations'));
    }
}
