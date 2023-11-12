<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ParticipantTournamentDetail extends Model
{
    use HasFactory;
    protected $table = 'participant_tournament_detail';

    public static function get_passes_participant($passes, $group_slug){
        return static::select(
            'participant_tournament_detail.id',
            'participant_tournament_detail.participant_id',
            'participant_tournament_detail.group_id',
            'participant_tournament_detail.time',
            'participant_tournament_detail.seat'
        )
        ->selectRaw('RANK() OVER (PARTITION BY participant_tournament_detail.seat ORDER BY participant_tournament_detail.time ASC) as row_num')
        ->join('tournament_groups', 'tournament_groups.id', '=', 'participant_tournament_detail.group_id')
        ->where('tournament_groups.slug', $group_slug)
        ->orderBy(DB::raw(
            " CASE
            WHEN participant_tournament_detail.time = '00:00:000' THEN 2
            ELSE 1
            END ASC,
            participant_tournament_detail.time"
        ), "ASC")
        ->get()
        ->groupBy('participant_tournament_detail.seat')
        ->flatMap(function ($group) use ($passes) {
            return $group->take($passes);
        });
    }


}
