<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->using(Membership::class)
            ->withPivot('id', 'joined_at', 'internal_role', 'left_at')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function owner()
    {
        return $this->memberships()->where('internal_role', 'owner')->first();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function expenses() {
        return $this->hasMany(Expense::class); 
    }

    public function settlements() {
        return $this->hasMany(Settlement::class); 
    }
}
