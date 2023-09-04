<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentParticipant extends Model
{
    use HasFactory;

    # tournament group controller
    public static function get_by_group_id($group_id)
    {
        return TournamentParticipant::select(
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
        )
            ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->get();
    }
    # tournament participant controller
    public static function total_participant()
    {
        $result = TournamentParticipant::select('tournament_participants.id')
            ->get();

        return $result->count();
    }
    # team member model
    public static function is_over_limit_participant_per_team_by_group($group, $team_slug)
    {
        $result = TournamentParticipant::select('tournament_participants.id')
            ->join('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $team_slug)
            ->where('tournament_participants.group_id', $group->id)
            ->get();

        return $result->count() >= $group->max_per_team;
    }
}
