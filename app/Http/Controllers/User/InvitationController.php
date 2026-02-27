<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use App\Mail\InviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    // send an invitation email to a new member
    public function send(Request $request, Colocation $colocation)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // check if the user is already in the colocation
        $alreadyMember = Membership::where('colocation_id', $colocation->id)
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })->exists();

        if ($alreadyMember) {
            return back()->with('error', 'This user is already a member.');
        }

        // create the invitation with a random token
        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => Str::random(64),
        ]);

        // send the invitation email
        Mail::to($request->email)->send(new InviteMail($invitation));

        return back()->with('success', "Invitation sent to {$request->email}!");
    }

    // show the page to accept or refuse an invitation
    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        return view('user.invitations.accept', compact('invitation'));
    }

    // add the current user to the colocation and remove the invitation
    public function process($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // create a membership for the current user
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $invitation->colocation_id,
            'internal_role' => 'member',
            'joined_at' => now(), 
        ]);

        // delete the invitation after it is used
        $invitation->delete();

        return redirect()->route('user.colocations.index')
            ->with('success', 'You have successfully joined the colocation!');
    }

    // refuse the invitation and delete it
    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        $invitation->delete();

        return redirect()->route('user.colocations.index')
            ->with('success', 'Invitation refused and removed.');
    }
}