<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status'];

    // a colocation has many users through the memberships table
    public function users()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->using(Membership::class)
            ->withPivot('id', 'joined_at', 'internal_role', 'left_at')
            ->withTimestamps();
    }

    // a colocation has many membership rows
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    // check if a user is the owner of this colocation
    public function isOwner($userId)
    {
        return $this->memberships()->where('user_id', $userId)->where('internal_role', 'owner')->exists();
    }

    // a colocation has many categories
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // a colocation has many invitations
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // a colocation has many expenses
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // a colocation has many settlements
    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
}