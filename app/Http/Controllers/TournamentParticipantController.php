<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TournamentParticipantController extends Controller
{
    public function create($tournament_slug, $group_slug)
    {
        return $tournament_slug;
    }
}
