<?php

namespace App\Models;

use App\Helpers\Format;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentGroup extends Model
{
    use HasFactory;

    public static function get_by_tournament_slug($request, $slug)
    {
        $key = $request->get('key') ?? '';
        return TournamentGroup::leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('tournament_groups.group', 'like', '%' . $r . '%');
                        $query->orWhere('tournament_groups.max_participant', 'like', '%' . $r . '%');
                        $query->orWhere('tournament_groups.max_per_group', 'like', '%' . $r . '%');
                        $query->orWhere('tournament_groups.description', 'like', '%' . $r . '%');
                    });
                }
            })
            ->where('tournaments.slug', $slug)
            ->orderBy($request->get('sort_at') ?? 'tournament_groups.group', $request->get('sort_by') ?? 'asc')
            ->paginate($request->get('limit') ?? 20);
    }
}
