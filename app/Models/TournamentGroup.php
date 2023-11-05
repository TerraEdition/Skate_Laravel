<?php

namespace App\Models;

use App\Helpers\Format;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TournamentGroup extends Model
{
    use HasFactory, Sluggable;
    protected $fillable = ['status'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'group'
            ]
        ];
    }
    # participant controller
    # tournament excel
    public static function get_all($status, $tournament_slug = false)
    {
        $result = static::select(
            'tournaments.tournament',
            'tournaments.start_date',
            'tournaments.end_date',
            'tournaments.location',
            'tournaments.slug as tournament_slug',
            'tournament_groups.group',
            'tournament_groups.gender',
            'tournament_groups.min_age',
            'tournament_groups.max_age',
            'tournament_groups.slug',
            DB::raw('(SELECT count(id) from tournament_participants where tournament_participants.group_id = tournament_groups.id ) as total_participant')
        )->leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id');
        if ($status == 'now') {
            $result->where('tournaments.start_date', '<=', Date("Y-m-d"));
            $result->where('tournaments.end_date', '>=', Date("Y-m-d"));
            $result->orWhere('tournament_groups.status', '1');
        } else if ($status == 'completed') {
            $result->where('tournaments.end_date', '<', Date("Y-m-d"));
            $result->orWhere('tournament_groups.status', '2');
        } else if ($status == 'incoming') {
            $result->where('tournaments.start_date', '>', Date("Y-m-d"));
            $result->where('tournament_groups.status', '0');
        }
        if ($tournament_slug) {
            return $result->where('tournaments.slug', $tournament_slug)->orderBy('tournament_groups.group', 'asc')->get();
        } else {
            return $result->orderBy('tournaments.start_date', 'asc')
                ->orderBy('tournament_groups.group', 'asc')
                ->orderBy('tournaments.tournament', 'asc')
                ->paginate(20);
        }
    }
    # tournament group controller
    public static function get_by_tournament_slug($request, $tournament_slug)
    {
        $key = $request->get('key') ?? '';
        return static::select(
            'tournament_groups.group',
            'tournament_groups.gender',
            'tournament_groups.min_age',
            'tournament_groups.max_age',
            'tournament_groups.slug',
            DB::raw('(SELECT count(id) from tournament_participants WHERE group_id = tournament_groups.id) AS total_participant')
        )->leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('tournament_groups.group', 'like', '%' . $r . '%');
                        $query->orWhere('tournament_groups.description', 'like', '%' . $r . '%');
                    });
                }
            })
            ->where('tournaments.slug', $tournament_slug)
            ->orderBy($request->get('sort_at') ?? 'tournament_groups.group', $request->get('sort_by') ?? 'asc')
            ->paginate($request->get('limit') ?? 20);
    }
    # tournament participant controller
    public static function get_id_by_tournament_slug_by_group_slug($tournament_slug, $slug)
    {
        return static::select(
            'tournament_groups.id',
        )
            ->leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id')
            ->leftJoin('tournament_participants', 'tournament_participants.group_id', '=', 'tournament_groups.id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('tournament_groups.slug', $slug)
            ->where('tournaments.slug', $tournament_slug)

            ->first();
    }
    # tournament group controller
    public static function get_by_tournament_slug_by_group_slug($tournament_slug, $slug)
    {
        return static::select(
            'tournament_groups.id',
            'tournaments.id as tournament_id',
            'tournaments.tournament',
            'tournament_groups.group',
            'tournament_groups.gender',
            'tournament_groups.min_age',
            'tournament_groups.max_age',
            'tournament_groups.status',
            'tournament_groups.slug',
            DB::raw('count(tournament_participants.id) AS total_participant'),
            DB::raw('COUNT(DISTINCT teams.id) as team_register'),
        )
            ->leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id')
            ->leftJoin('tournament_participants', 'tournament_participants.group_id', '=', 'tournament_groups.id')
            ->leftJoin('team_members', 'team_members.id', '=', 'tournament_participants.member_id')
            ->leftJoin('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('tournament_groups.slug', $slug)
            ->where('tournaments.slug', $tournament_slug)
            ->groupBy(
                'tournaments.id',
                'tournament_groups.id',
                'tournaments.tournament',
                'tournament_groups.group',
                'tournament_groups.gender',
                'tournament_groups.min_age',
                'tournament_groups.max_age',
                'tournament_groups.status',
                'tournament_groups.slug',
            )
            ->first();
    }

    # team controller
    public static function get_all_by_tournament_name($status, $tournament)
    {
        $result = TournamentGroup::select(
            'tournaments.id as tournament_id',
            'tournaments.tournament',
            'tournaments.start_date',
            'tournaments.end_date',
            'tournaments.location',
            'tournaments.slug as tournament_slug',
            'tournament_groups.id as group_id',
            'tournament_groups.group',
            'tournament_groups.gender',
            'tournament_groups.min_age',
            'tournament_groups.max_age',
            'tournament_groups.slug',
            DB::raw('(SELECT count(id) from tournament_participants where tournament_participants.group_id = tournament_groups.id ) as total_participant')
        )->leftJoin('tournaments', 'tournament_groups.tournament_id', '=', 'tournaments.id');

        if ($status == 'now') {
            $result->where('tournaments.start_date', '<=', Date("Y-m-d"));
            $result->where('tournaments.end_date', '>=', Date("Y-m-d"));
            $result->orWhere('tournament_groups.status', '1');
        } else if ($status == 'completed') {
            $result->where('tournaments.end_date', '<', Date("Y-m-d"));
            $result->orWhere('tournament_groups.status', '2');
        } else if ($status == 'incoming') {
            $result->where('tournaments.start_date', '>', Date("Y-m-d"));
            $result->where('tournament_groups.status', '0');
        }
        if ($tournament) {
            return $result->where('tournaments.tournament', $tournament)->orderBy('tournament_groups.group', 'asc')->get();
        } else {
            return $result->orderBy('tournaments.start_date', 'asc')
                ->orderBy('tournament_groups.group', 'asc')
                ->orderBy('tournaments.tournament', 'asc')
                ->paginate(20);
        }
    }
}
