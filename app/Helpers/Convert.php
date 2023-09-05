<?php

namespace App\Helpers;

use App\Models\CoreDB\User as UserPH45;
use App\Models\Setting\UserAccess\UserPH4;

class Convert
{
    # team controller
    public static function gender($val, $to_id = true)
    {
        $statuses = [
            'Putra dan Putri' => '0',
            'Putra' => '1',
            'Putri' => '2',
        ];

        if ($to_id) {
            return $statuses[$val] ?? '0';
        } else {
            return array_search($val, $statuses) ?? 'unknown';
        }
    }
    # Participant Index Viw
    public static function status_tournament($val, $to_id = true)
    {
        $statuses = [
            'Sekarang' => 'now',
            'Akan Datang' => 'incoming',
            'Selesai' => 'completed',
        ];
        if ($to_id) {
            return $statuses[$val] ?? '0';
        } else {
            return array_search($val, $statuses) ?? 'unknown';
        }
    }
}
