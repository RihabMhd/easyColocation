<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'reputation_score',
        'is_banned',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class); 
    }

    // the colocations this user belongs to through the memberships table
    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'memberships')
            ->using(Membership::class)
            ->withPivot('id', 'joined_at', 'internal_role', 'left_at')
            ->withTimestamps();
    }

    // check if this user is an admin
    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    // check if this user is a user
    public function isUser(): bool
    {
        return $this->role_id === 2;
    }

    // all expenses paid by this user
    public function paidExpenses() {
        return $this->hasMany(Expense::class); 
    }

    // all settlements where this user owes money
    public function debts() {
        return $this->hasMany(Settlement::class, 'debtor_id'); 
    }

    // all settlements where others owe money to this user
    public function credits() {
        return $this->hasMany(Settlement::class, 'creditor_id'); 
    }
}
