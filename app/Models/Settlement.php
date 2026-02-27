<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    use HasFactory;

    protected $fillable = ['colocation_id', 'debtor_id', 'creditor_id', 'expense_id', 'amount', 'is_paid'];

    // the expense that created this settlement
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    // the user who owes money
    public function debtor()
    {
        return $this->belongsTo(User::class, 'debtor_id');
    }

    // the user who paid and must receive money
    public function creditor()
    {
        return $this->belongsTo(User::class, 'creditor_id');
    }
}