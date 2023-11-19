<?php

namespace App\Helpers;

use App\Models\TournamentParticipant;

class Model
{
    # setup finalize view
    # participant detail
    public static function ParticipantPerSeat($group_slug,$seat)
    {
        return TournamentParticipant::get_by_group_slug($group_slug,true,$seat);
    }
    # participant detail
    public static function ParticipantPerFinal($group_slug)
    {
        return TournamentParticipant::get_final_by_group_slug($group_slug,true,1);
    }
}
