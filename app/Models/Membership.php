<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

// membership is a pivot model that links users and colocations
class Membership extends Pivot
{
    // allow auto increment ids on this pivot table
    public $incrementing = true;

    protected $fillable = ['user_id', 'colocation_id', 'joined_at', 'internal_role', 'left_at'];

    // the name of the table in the database
    protected $table = 'memberships';

    // the user linked to this membership
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // the colocation linked to this membership
    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }
}