<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class,'memberships')
        ->using(Membership::class)
        ->withPivot('id', 'joined_at', 'internal_role', 'left_at')
        ->withTimestamps();
    }
}
