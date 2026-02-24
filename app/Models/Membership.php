<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    public $incrementing = true;
    protected $table = 'memberships';
}
