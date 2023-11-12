<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingGroupRound extends Model
{
    use HasFactory;

    # Setting Group Round controller
    # participant controller
    public static function get_by_group_slug($group_slug)
    {
        return static::select(
            'setting_group_rounds.id',
            'setting_group_rounds.group_id',
            'setting_group_rounds.passes',
            'setting_group_rounds.round',
            'tournament_groups.slug',
            DB::raw('max(participant_tournament_detail.seat) as total_seat'),
        )
        ->leftJoin('tournament_groups', 'tournament_groups.id', '=', 'setting_group_rounds.group_id')
        ->leftJoin('tournament_participants', 'tournament_participants.group_id', '=', 'setting_group_rounds.group_id')
        ->leftJoin('participant_tournament_detail', 'participant_tournament_detail.participant_id', '=', 'tournament_participants.id')
        ->where('tournament_groups.slug', $group_slug)
        ->groupBy(
            'setting_group_rounds.id',
            'setting_group_rounds.group_id',
            'setting_group_rounds.passes',
            'setting_group_rounds.round',
            'tournament_groups.slug',
        )->first();
    }
}
