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
    public static function get_by_group_slug($group_slug, $order_by_time = false,$seat = 0)
    {
        $result = static::select(
            'tournament_participants.id as participant_id',
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
            DB::raw('COALESCE(participant_tournament_detail.time, "00:00:000") as time'), // Provide a default value for time
            'tournament_participants.no_participant',
            DB::raw('COALESCE(participant_tournament_detail.seat, "") as seat'), // Provide a default value for seat
        )
        ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
        ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
        ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
        ->leftJoin('participant_tournament_detail', 'participant_tournament_detail.participant_id', '=', 'tournament_participants.id')
        ->where('tournament_groups.slug', $group_slug);
        if($seat!='finish'){
        $result->where(function ($query) {
            $query->where('participant_tournament_detail.round', '1')
            ->orWhereNull('participant_tournament_detail.round'); // Include rows where round is null
        });
    }else{
        $result->where(function ($query) {
            $query->where('participant_tournament_detail.round', function ($subquery) {
                $subquery->select(DB::raw('max(round)'))
                ->from('participant_tournament_detail')
                ->whereColumn('participant_tournament_detail.group_id', 'tournament_groups.id');
            });
        });
    }
        if($seat>0){
            $result->where('participant_tournament_detail.seat', $seat);
        }

        if ($order_by_time) {
            $result->orderBy(DB::raw(
                " CASE
                WHEN participant_tournament_detail.time = '00:00:000' THEN 2
                ELSE 1
                END ASC,
                participant_tournament_detail.time"
            ), "ASC");
        }else{
            $result->orderBy('participant_tournament_detail.seat','asc');
        }
        $result->orderBy('tournament_participants.no_participant','asc');
        return $result->get();
    }
    # participant controller
    public static function get_final_by_group_slug($group_slug, $order_by_time = false,$seat = 0)
    {
        $result = static::select(
            'tournament_participants.id as participant_id',
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
            DB::raw('COALESCE(participant_tournament_detail.time, "00:00:000") as time'), // Provide a default value for time
            'tournament_participants.no_participant',
            DB::raw('COALESCE(participant_tournament_detail.seat, "") as seat'), // Provide a default value for seat
        )
        ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
        ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
        ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
        ->leftJoin('participant_tournament_detail', 'participant_tournament_detail.participant_id', '=', 'tournament_participants.id')
        ->where('tournament_groups.slug', $group_slug)
        ->where(function ($query) {
            $query->where('participant_tournament_detail.round', function ($subquery) {
                $subquery->select(DB::raw('max(round)'))
                    ->from('participant_tournament_detail')
                    ->whereColumn('participant_tournament_detail.group_id', 'tournament_groups.id');
            });
        });

        if($seat>0){
            $result->where('participant_tournament_detail.seat', $seat);
        }
        $result->where('participant_tournament_detail.round', '2');

        if ($order_by_time) {
            $result->orderBy(DB::raw(
                " CASE
                WHEN participant_tournament_detail.time = '00:00:000' THEN 2
                ELSE 1
                END ASC,
                participant_tournament_detail.time"
            ), "ASC");
        }else{
            $result->orderBy('participant_tournament_detail.seat','asc');
        }
        $result->orderBy('tournament_participants.no_participant','asc');
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
        return static::select('tournament_participants.id', 'team_members.member','teams.team', 'teams.team_initial')
            ->join('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->join('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->where('tournament_groups.slug', $group_slug)
            ->orderBy('tournament_participants.no_participant', 'asc')
            ->orderBy('tournament_participants.id', 'asc')
            ->get();
    }
    # API Participant Controller
    public static function get_by_group_id($group_id)
    {
        $result =  static::select(
            'tournament_participants.id as participant_id',
            'tournament_groups.group',
            'teams.team',
            'team_members.birth',
            'team_members.gender',
            'team_members.member',
            'participant_tournament_detail.time',
            'tournament_participants.no_participant',
            'participant_tournament_detail.seat',
        )
            ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->leftJoin('participant_tournament_detail', 'participant_tournament_detail.participant_id', '=', 'tournament_participants.id')
            ->where('tournament_groups.id', $group_id)
            ->where(function ($query) {
                $query->where('participant_tournament_detail.round', '1')
                    ->orWhereNull('participant_tournament_detail.round');
            })
            ->orderBy(DB::raw(
                "CASE
                WHEN participant_tournament_detail.time = '00:00:000' THEN 2
                ELSE 1
                END ASC,
                participant_tournament_detail.time"
            ), "ASC");
            $result->orderBy('tournament_participants.no_participant','asc');
        return $result->get();
    }

    # team member controller
    public static function get_tournament_by_member_slug($member_slug){
        return static::select('tournament_groups.group','tournaments.tournament','tournaments.start_date','tournaments.end_date')
            ->join('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->join('tournament_groups', 'tournament_groups.id', '=', 'tournament_participants.group_id')
            ->join('tournaments', 'tournaments.id', '=', 'tournament_groups.tournament_id')
            ->where('team_members.slug', $member_slug)
            ->orderBy('tournament_groups.group', 'desc')
            ->paginate(20);
    }
}
