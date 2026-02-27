<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['colocation_id', 'user_id', 'category_id', 'title', 'amount', 'date'];

    // the user who paid for this expense
    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // the colocation this expense belongs to
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    // the category of this expense
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // the settlements created from this expense
    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
}