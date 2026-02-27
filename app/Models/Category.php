<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'colocation_id'];

    // a category belongs to one colocation
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    // a category has many expenses
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}