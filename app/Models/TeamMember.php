<?php

namespace App\Models;

use App\Helpers\Format;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return TeamMember::select('team_members.*')
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

    # tournament participant controller
    public static function get_member_by_rule_group_id($group)
    {
        $min_birth = Carbon::now()->subYears($group->min_age);
        $max_birth = Carbon::now()->subYears($group->max_age);
        $gender = $group->gender;
        if ($gender == 0) {
            $gender = ['1', '2'];
        } else {
            $gender = [$gender];
        }
        return TeamMember::select('id', 'member')
            ->where('team_id', '1')
            ->whereIn('gender', $gender)
            ->where('birth', '>=', $max_birth)
            ->where('birth', '<=', $min_birth)
            ->whereRaw('id NOT IN ( SELECT member_id FROM tournament_participants where group_id = ' . $group->id . ')')
            ->orderBy('member', 'asc')->get();
    }
}
