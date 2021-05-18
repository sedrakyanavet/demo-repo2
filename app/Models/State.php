<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class State extends Mongo
{
    use HasFactory;

    protected $guarded = [];

    public function nestedManyPlayers(\Closure $next = null)
    {
        return $this->hasManyNested(Player::class, $next);
    }

    public function nestedManyBanks(\Closure $next = null)
    {
        return $this->hasManyNested(Bank::class, $next);
    }

    public function nestedPlayer(\Closure $next = null)
    {
        return $this->hasNested(Player::class, $next);
    }

    public function nestedMoney(\Closure $next = null)
    {
        return $this->hasNested(Money::class, $next);
    }
}
