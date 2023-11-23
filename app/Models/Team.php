<?php

namespace App\Models;

use App\Helpers\Format;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Team extends Model
{
    use HasFactory, Sluggable;
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'team'
            ]
        ];
    }
    # team controller
    public static function get_all($request)
    {
        $key = $request->get('key') ?? '';

        $result = Team::select('slug', 'team', 'updated_at', 'team_initial')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('team', 'like', '%' . $r . '%');
                    });
                }
            });

        $result->orderBy($request->get('sort_at') ?? 'id', $request->get('sort_by') ?? 'desc');
        return $result->paginate($request->get('limit') ?? 20);
    }

    # team controller
    public static function get_detail_by_slug($slug)
    {
        $result = Team::select(
            'teams.*',
            DB::raw('(select CONCAT(contact_persons.phone," (",contact_persons.name,")") from contact_persons where contact_persons.team_id = teams.id order by id desc limit 1) as phone'),
        )
            ->where('slug', $slug)
            ->first();

        return $result;
    }
    # team controller
    public static function get_by_team_name($team)
    {
        $result = Team::select(
            'teams.*',
        )
            ->where('team', $team)
            ->first();

        return $result;
    }
}
