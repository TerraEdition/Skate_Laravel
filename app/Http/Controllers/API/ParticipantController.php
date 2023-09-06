<?php

namespace App\Http\Controllers\API;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\TournamentParticipant;
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
            ], [], [
                'participant_id' => 'peserta',
                'time' => 'waktu',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return Response::make(400, $validator->errors());
            }
            # save time
            $save_time_participant = TournamentParticipant::where('id', $request->get('participant_id'))->first();
            if (empty($save_time_participant)) {
                return Response::make(400, __('global.participant_not_found'));
            }
            $save_time_participant->time = $request->get('time');
            $save_time_participant->update();
            return Response::make(200, __('global.participant_updated'));
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
}
