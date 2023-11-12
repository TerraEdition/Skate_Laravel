<?php

namespace App\Helpers;

use App\Models\TournamentParticipant;

class Model
{
    # setup finalize view
    public static function ParticipantPerSeat($group_slug,$seat)
    {
        return TournamentParticipant::get_by_group_slug($group_slug,true,$seat);
    }
}
