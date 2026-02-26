<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['colocation_id', 'user_id', 'category_id', 'title', 'amount', 'date'];

    public function payer() {
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function colocation(){
        return $this->belongsTo(Colocation::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function settlements(){
        return $this->hasMany(Settlement::class);
    }

}
