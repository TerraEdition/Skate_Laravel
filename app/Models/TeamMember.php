<?php

namespace App\Models;

use App\Helpers\Convert;
use App\Helpers\Date;
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
    public static function get_by_team_slug($slug)
    {
        $result = [];
        TeamMember::join('teams', 'teams.id', '=', 'team_members.team_id')
            ->where('teams.slug', $slug)
            ->orderBy('team_members.member', 'asc')
            ->chunk(100, function ($order) use (&$result) {
                foreach ($order as $a) {
                    $result[] = [
                        'member' => $a['member'],
                        'address' => $a['address'],
                        'phone' => $a['phone'],
                        'email' => $a['email'],
                        'gender' => Convert::gender($a['gender'], false),
                        'image' => $a['image'],
                        'birth' => Date::format_long($a['birth']),
                        'age' => Date::calculate_age($a['birth']),
                        'slug' => $a['slug'],
                    ];
                }
            });
        return $result;
    }
}
