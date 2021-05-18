<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\State;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $state = new State();
        $state->nestedManyPlayers()->push(new Player(['id' => 8]));
        $state->nestedPlayer(function ($player){
            $player->nestedBank();
        });

        $state->nestedMoney();

        $state->save();
        dd($state);
    }
}
