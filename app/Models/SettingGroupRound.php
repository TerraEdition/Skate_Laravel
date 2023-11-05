<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingGroupRound extends Model
{
    use HasFactory;

    public static function get_by_group_slug($group_slug)
    {
        return static::select('setting_group_rounds.*')->where('tournament_groups.slug', $group_slug)
            ->join('tournament_groups', 'tournament_groups.id', '=', 'setting_group_rounds.group_id')
            ->first();
    }
}
