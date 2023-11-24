<?php

namespace App\Models;

use App\Helpers\Format;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamMember extends Model
{
    use HasFactory, Sluggable;
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'member'
            ]
        ];
    }
    # member controller
    public static function get_by_team_slug($request, $slug)
    {
        $key = $request->get('key') ?? '';
        return TeamMember::select(
            'team_members.*',
            DB::raw('(SELECT COUNT(tournament_participants.id) FROM tournament_participants WHERE tournament_participants.member_id = team_members.id) as total_tournament'),
        )
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('member', 'like', '%' . $r . '%');
                    });
                }
            })
            ->where('teams.slug', $slug)
            ->orderBy($request->get('sort_at') ?? 'team_members.member', $request->get('sort_by') ?? 'asc')
            ->paginate($request->get('limit') ?? 20);
    }
    # tournament excel export
    public static function get_all_member_by_team_slug($slug)
    {
        return TeamMember::select('team_members.*', 'teams.team')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $slug)->get();
    }

    public static function get_all_by_team_slug($slug)
    {
        return TeamMember::select('teams.team', 'team_members.*')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $slug)
            ->orderBy('team_members.member', 'asc')->get();
    }

    # tournament participant controller
    public static function get_member_by_rule_group($group, $team_slug)
    {
        $min_birth = $group->min_age;
        $max_birth = $group->max_age;
        $gender = $group->gender;
        if ($gender == 0) {
            $gender = ['1', '2'];
        } else {
            $gender = [$gender];
        }
        return TeamMember::select('team_members.id', 'team_members.member')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $team_slug)
            ->whereIn('gender', $gender)
            ->whereRaw('team_members.birth >= ' . $min_birth)
            ->whereRaw('team_members.birth <= ' . $max_birth)
            ->whereRaw('team_members.id NOT IN ( SELECT member_id FROM tournament_participants where group_id = ' . $group->id . ')')
            ->orderBy('team_members.member', 'ASC')->get();
    }

    # team controller
    public static function get_id_by_member_name_by_team_slug($member, $team_slug)
    {
        return TeamMember::select('teams.id', 'team_members.member', 'team_members.gender', 'teams.team', 'team_members.birth', 'teams.slug as team_slug')
            ->join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $team_slug)
            ->where('team_members.member', $member)->first();
    }
}
