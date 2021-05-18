<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Player extends Mongo
{
    use HasFactory;
    protected $guarded = [];

    public function nestedBank(\Closure $next = null)
    {
        return $this->hasNested(Bank::class, $next);
    }
}
