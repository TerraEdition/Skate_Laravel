<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentGallery extends Model
{
    use HasFactory;

    public static function get_by_tournament_slug($tournament_slug)
    {
        return static::select('tournament_galleries.*')
            ->join('tournaments', 'tournaments.id', '=', 'tournament_galleries.tournament_id')
            ->where('tournaments.slug', $tournament_slug)->get();
    }
}
