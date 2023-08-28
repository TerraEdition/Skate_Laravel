<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TournamentParticipantController extends Controller
{
    public function create($tournament_slug, $group_slug)
    {
        try {
            # check tournament exist
            $tournament = Tournament::where('slug', $tournament_slug)->first();
            # check if validation fails
            if (empty($tournament)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_not_found'));
                return redirect()->back();
            }
            # check group exist
            $group = TournamentGroup::where('slug', $group_slug)->first();
            # check if validation fails
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.group_not_found'));
                return redirect()->back();
            }
            $data = [
                'tournament_slug' => $tournament_slug,
                'group' => $group,
            ];
            return view('Dashboard.Tournament.Group.Participant.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}