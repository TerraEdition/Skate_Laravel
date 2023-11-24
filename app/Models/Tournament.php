<?php

namespace App\Models;

use App\Helpers\Format;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tournament extends Model
{
    use HasFactory, Sluggable;
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'tournament'
            ]
        ];
    }

    # tournament controller
    public static function get_all($request)
    {
        $key = $request->get('key') ?? '';

        $result = Tournament::select(
            'tournaments.tournament',
            'tournaments.start_date',
            'tournaments.end_date',
            'tournaments.location',
            'tournaments.slug',
            DB::raw('(SELECT COUNT(tournament_groups.id) FROM tournament_groups WHERE tournament_groups.tournament_id = tournaments.id) AS total_group ')
        )
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('tournaments.tournament', 'like', '%' . $r . '%');
                        $query->orWhere('tournaments.location', 'like', '%' . $r . '%');
                        $query->orWhere('tournaments.start_date', 'like', '%' . $r . '%');
                        $query->orWhere('tournaments.end_date', 'like', '%' . $r . '%');
                        $query->orWhere('tournaments.description', 'like', '%' . $r . '%');
                    });
                }
            });
        return $result
            ->orderBy($request->get('sort_at') ?? 'id', $request->get('sort_by') ?? 'desc')
            ->paginate($request->get('limit') ?? 20)->withQueryString();
    }

    # tournament controller
    public static function get_detail_by_slug($slug)
    {
        $result = Tournament::select(
            'tournaments.id',
            'tournaments.tournament',
            'tournaments.start_date',
            'tournaments.end_date',
            'tournaments.location',
            'tournaments.description',
            'tournaments.slug',
        )
            ->where('tournaments.slug', $slug)
            ->groupBy(
                'tournaments.id',
                'tournaments.tournament',
                'tournaments.start_date',
                'tournaments.end_date',
                'tournaments.location',
                'tournaments.description',
                'tournaments.slug',
            );
        return $result->first();
    }

    public static function get_near_tournament()
    {
        $result = Tournament::select(
            'tournaments.id',
            'tournaments.tournament',
            'tournaments.start_date',
            'tournaments.end_date',
            'tournaments.location',
            'tournaments.description',
            'tournaments.slug',
        )
            ->where('tournaments.start_date', '>', date("Y-m-d"))
            ->groupBy(
                'tournaments.id',
                'tournaments.tournament',
                'tournaments.start_date',
                'tournaments.end_date',
                'tournaments.location',
                'tournaments.description',
                'tournaments.slug',
            );
        return $result->first();
    }
}
