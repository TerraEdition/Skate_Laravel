<?php

namespace App\Models;

use App\Helpers\Format;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        $result = Team::select('slug', 'team', 'updated_at')
            ->where(function ($query) use ($key) {
                $key = explode(' ', Format::clean_char_search($key));
                foreach ($key as $r) {
                    $query->where(function ($query) use ($r) {
                        $query->orWhere('team', 'like', '%' . $r . '%');
                    });
                }
            });

        $result
            // ->orderBy($request->get('sort_at') ?? 'id', $request->get('sort_by') ?? 'desc')
            ->paginate($request->get('limit') ?? 20);
        return  $result->get();
    }
}
