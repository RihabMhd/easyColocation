<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;

use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    // mark a settlement as paid and increase the debtor reputation
    public function update(Request $request, Settlement $settlement)
    {
        // only the debtor or the creditor can update this settlement
        if ($settlement->debtor_id !== auth()->id() && $settlement->creditor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // only update if the settlement is not already paid
        if (!$settlement->is_paid) {
            $settlement->update(['is_paid' => true]);

            // give one reputation point to the debtor
            $debtor = User::find($settlement->debtor_id);
            $debtor->increment('reputation_score');
        }

        return back()->with('success', 'Payment status updated and reputation increased!');
    }
}