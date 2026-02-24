<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    public $incrementing = true;
    protected $fillable = ['user_id', 'colocation_id', 'joined_at', 'internal_role', 'left_at'];
    protected $table = 'memberships';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}
