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
        $result =  static::select(
            'tournament_participants.id as participant_id',
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
            'tournament_participants.time',
            'tournament_participants.no_participant',
            'setting_group_rounds.seat',
        )
            ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->leftJoin('setting_group_rounds', 'setting_group_rounds.participant_id', '=', 'tournament_participants.id')
            ->where('tournament_groups.slug', $group_slug)
            ->where(function ($query) {
                $query->where('setting_group_rounds.round', '1')
                    ->orWhereNull('setting_group_rounds.round');
            });

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
        $result = static::select('tournament_participants.id')
            ->get();

        return $result->count();
    }

    # import excel
    public static function get_participant_by_name_by_team_by_group_slug($member_name, $team_name, $group_slug)
    {
        return static::select('tournament_participants.id')
            ->join('team_members', 'tournament_participants.member_id', '=', 'team_members.id')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->join('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->where('team_members.member', $member_name)
            ->where('teams.team', $team_name)
            ->where('tournament_groups.slug', $group_slug)
            ->first();
    }
    # Setting Group Round Controller
    public static function get_all_participant_by_group_slug($group_slug)
    {
        return static::select('tournament_participants.id', 'team_members.member', 'teams.team_initial')
            ->join('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->join('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->where('tournament_groups.slug', $group_slug)
            ->orderBy('tournament_participants.no_participant', 'asc')
            ->orderBy('tournament_participants.id', 'asc')
            ->get();
    }
}
