<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{

    public function update(Request $request, Settlement $settlement)
    {
        if ($settlement->debtor_id !== auth()->id() && $settlement->creditor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $settlement->update([
            'is_paid' => true
        ]);

        return back()->with('success', 'Payment status updated!');
    }
}
