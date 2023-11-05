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

        $result = Tournament::select('tournament', 'start_date', 'end_date', 'location', 'slug')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('tournament', 'like', '%' . $r . '%');
                        $query->orWhere('location', 'like', '%' . $r . '%');
                        $query->orWhere('start_date', 'like', '%' . $r . '%');
                        $query->orWhere('end_date', 'like', '%' . $r . '%');
                        $query->orWhere('description', 'like', '%' . $r . '%');
                    });
                }
            });
        return $result
            ->orderBy($request->get('sort_at') ?? 'id', $request->get('sort_by') ?? 'desc')
            ->paginate($request->get('limit') ?? 20);
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
