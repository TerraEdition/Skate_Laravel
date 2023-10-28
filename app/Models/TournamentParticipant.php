<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TournamentParticipant extends Model
{
    use HasFactory;

    # tournament participant controller
    # participant excel
    public static function get_by_group_slug($group_slug, $order_by_time = false)
    {
        $result =  TournamentParticipant::select(
            'tournament_participants.id as participant_id',
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
            'tournament_participants.time',
        )
            ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('tournament_groups.slug', $group_slug);

        if ($order_by_time) {
            $result->orderBy(DB::raw(
                " CASE
                WHEN tournament_participants.time = '' THEN 2
                ELSE 1
                END ASC,
                tournament_participants.time"
            ), "ASC");
        }
        return $result->get();
    }
    # tournament participant controller
    public static function total_participant()
    {
        $result = TournamentParticipant::select('tournament_participants.id')
            ->get();

        return $result->count();
    }
}
