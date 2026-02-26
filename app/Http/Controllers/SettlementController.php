<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{

    public function update(Request $request, Settlement $settlement)
    {
        if ($settlement->debtor_id !== Auth::id()) {
            abort(403, 'You can only mark your own debts as paid.');
        }

        $settlement->update([
            'is_paid' => true
        ]);

        return back()->with('success', 'Expense part marked as paid!');
    }

}