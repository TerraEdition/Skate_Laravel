<?php

namespace App\Models;

use App\Helpers\Format;
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
}
