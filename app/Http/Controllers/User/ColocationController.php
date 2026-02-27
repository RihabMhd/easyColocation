<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\Colocation;
use App\Http\Requests\UpdateColocationRequest;
use App\Http\Requests\StoreColocationRequest;
use App\Models\Settlement;
use App\Models\Membership;
use App\Models\User;

class ColocationController extends Controller
{
    // show all active colocations for the current user
    public function index()
    {
        $colocations = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'active')->get();

        return view('user.colocations.index', compact('colocations'));
    }

    // create a new colocation for the current user
    public function store(StoreColocationRequest $request)
    {
        // check if the user already has an active colocation with other members
        $activeColocation = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })
            ->where('status', 'active')
            ->withCount('memberships')
            ->first();

        // block if the current colocation still has other members
        if ($activeColocation && $activeColocation->memberships_count > 1) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot create a new colocation while your current one still has other active members.'])
                ->withInput();
        }

        // cancel the old empty colocation before making a new one
        if ($activeColocation) {
            $activeColocation->update(['status' => 'cancelled']);
        }

        $colocation = Colocation::create(array_merge(
            $request->validated(),
            ['status' => 'active']
        ));

        // add the current user as the owner of the new colocation
        $colocation->memberships()->create([
            'user_id' => auth()->id(),
            'joined_at' => now(),
            'internal_role' => 'owner',
        ]);

        return redirect()->route('user.colocations.index')
            ->with('success', 'New colocation created! Your previous solo session was archived.');
    }

    // show a single colocation with its members
    public function show(Colocation $colocation)
    {
        $colocation->load('memberships.user');
        return view('user.colocations.show', compact('colocation'));
    }

    // update the colocation details
    public function update(UpdateColocationRequest $request, Colocation $colocation)
    {
        $this->authorize('update', $colocation);
        $colocation->update($request->validated());

        return redirect()->route('user.colocations.index')
            ->with('success', 'Colocation updated successfully.');
    }

    // give the owner role to another member
    public function transferOwnership(Colocation $colocation, User $user)
    {
        // check that the current user is the owner
        $currentOwner = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->firstOrFail();

        // find the new owner in the colocation
        $newOwnerMembership = $colocation->memberships()
            ->where('user_id', $user->id)
            ->firstOrFail();

        // change roles between the two users
        $currentOwner->update(['internal_role' => 'member']);
        $newOwnerMembership->update(['internal_role' => 'owner']);

        return redirect()->back()->with('success', 'Ownership transferred successfully.');
    }

    // remove a member from the colocation
    public function removeMember(Colocation $colocation, User $user)
    {
        // only the owner can remove a member
        $isOwner = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->exists();

        if (!$isOwner) {
            return back()->with('error', 'Only the owner can remove members.');
        }

        // check if the member has unpaid debts
        $hasDebt = Settlement::where('colocation_id', $colocation->id)
            ->where('debtor_id', $user->id)
            ->where('is_paid', false)
            ->exists();

        if ($hasDebt) {
            // reduce the owner reputation and move the debts to the owner
            /** @var \App\Models\User $currentUser */
            $currentUser = auth()->user();
            $currentUser->decrement('reputation_score');

            Settlement::where('colocation_id', $colocation->id)
                ->where('debtor_id', $user->id)
                ->where('is_paid', false)
                ->update(['debtor_id' => auth()->id()]);
        }

        // remove the member from the colocation
        $colocation->memberships()->where('user_id', $user->id)->delete();

        return back()->with('success', "Member removed. Your reputation decreased by 1.");
    }

    // let the current user leave the colocation
    public function quit(Colocation $colocation)
    {
        $colocation->loadCount('memberships');
        $membership = $colocation->memberships()->where('user_id', auth()->id())->firstOrFail();

        // the owner must transfer ownership before leaving if there are other members
        if ($membership->internal_role === 'owner' && $colocation->memberships_count > 1) {
            return redirect()->back()->with('error', 'You must transfer ownership before leaving.');
        }

        // check if the user has unpaid debts
        $hasDebt = Settlement::where('colocation_id', $colocation->id)
            ->where('debtor_id', auth()->id())
            ->where('is_paid', false)
            ->exists();

        // reduce reputation if the user leaves with unpaid debts
        if ($hasDebt) {
            /** @var \App\Models\User $currentUser */
            $currentUser = auth()->user();
            $currentUser->decrement('reputation_score');
        }

        $membership->delete();

        // cancel the colocation if no members are left
        if ($colocation->memberships_count <= 1) {
            $colocation->update(['status' => 'cancelled']);
        }

        return redirect()->route('user.colocations.index')->with('success', 'You have left.');
    }

    // cancel the colocation - only the owner can do this
    public function destroy(Colocation $colocation)
    {
        $colocation->loadCount('memberships');

        // check that the current user is the owner
        $isOwner = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->where('internal_role', 'owner')
            ->exists();

        if (!$isOwner) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // cannot cancel if there are still other members
        if ($colocation->memberships_count > 1) {
            return redirect()->back()->with('error', 'Cannot cancel a colocation that still has other members. Transfer ownership or remove them first.');
        }

        $updated = $colocation->update(['status' => 'cancelled']);

        if ($updated) {
            return redirect()->route('user.colocations.index')->with('success', 'Colocation archived successfully.');
        }

        return redirect()->back()->with('error', 'Failed to update status.');
    }

    // show all cancelled colocations for the current user
    public function historique()
    {
        $colocations = Colocation::whereHas('memberships', function ($query) {
            $query->where('user_id', auth()->id());
        })->where('status', 'cancelled')->get();

        return view('user.colocations.historique', compact('colocations'));
    }
}