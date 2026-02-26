<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use App\Mail\InviteMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{

    public function send(Request $request, Colocation $colocation)
    {
        $request->validate([
            'email' => 'required|email'
        ]);


        $alreadyMember = Membership::where('colocation_id', $colocation->id)
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })->exists();

        if ($alreadyMember) {
            return back()->with('error', 'This user is already a member.');
        }


        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $request->email,
            'token' => Str::random(64),
        ]);


        Mail::to($request->email)->send(new InviteMail($invitation));

        return back()->with('success', "Invitation sent to {$request->email}!");
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();
        return view('invitations.accept', compact('invitation'));
    }

    public function process($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

       
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $invitation->colocation_id,
            'internal_role' => 'member',
            'joined_at' => now(), 
        ]);

     
        $invitation->delete();

        return redirect()->route('colocations.index')
            ->with('success', 'You have successfully joined the colocation!');
    }


    public function refuse($token)
    {

        $invitation = Invitation::where('token', $token)->firstOrFail();

        $invitation->delete();

        return redirect()->route('colocations.index')
            ->with('success', 'Invitation refused and removed.');
    }
}
