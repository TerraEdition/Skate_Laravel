<?php

namespace App\Http\Controllers\API;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use App\Models\ParticipantTournamentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function save_time_participant(Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'participant_id' => 'required|integer',
                'time' => 'required',
                'round'=>'required|integer'
            ], [], [
                'participant_id' => 'peserta',
                'time' => 'waktu',
                'round' => 'babak',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return Response::make(400, $validator->errors());
            }
            # save time
            $save_time_participant = ParticipantTournamentDetail::where('participant_id', $request->get('participant_id'))
            ->where('round',$request->get('round'))
            ->first();
            if (empty($save_time_participant)) {
                return Response::make(400, __('global.participant_not_found'));
            }
            $save_time_participant->time = $request->get('time');
            $save_time_participant->update();
            # set live score
            $this->_set_cache_live_score($save_time_participant->group_id);
            return Response::make(200, __('global.participant_updated'));
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
    private function _set_cache_live_score($group_id)
    {
        $participant = TournamentParticipant::get_by_group_id($group_id);
        cache()->forever('score_grup_' . $group_id, $participant);
    }

    public function get_live_score($group_id)
    {
        $participant = cache()->get('score_grup_' . $group_id);
        if (empty($participant)) {
            $participant = TournamentParticipant::get_by_group_id($group_id);
        }
        if (empty($participant)) {
            $participant = [];
        }

        $data = [
            'participant' => $participant,
        ];
        $htmlData = view('Home.DisplayScore', $data)->render();

        return Response::make(200, __('global.success'), ['html' => $htmlData]);
    }
}
