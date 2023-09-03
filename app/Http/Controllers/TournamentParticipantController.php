<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
                'teams' => Team::select('id', 'team')->orderBy('team', 'asc')->get(),
                'members_team' => TeamMember::get_member_by_rule_group_id($group),
            ];
            return view('Dashboard.Tournament.Group.Participant.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function store(Request $request, $tournament_slug, $group_slug)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|integer',
                'member_id' => 'required|integer',
            ], [], [
                'team_id' => 'Tim',
                'member_id' => 'Anggota Tim',
            ]);

            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            # check group
            $group = TournamentGroup::where('slug', $group_slug)->first();
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.group_not_found'));
                return redirect()->back();
            }
            # save tournament_participants
            $save_tournament_participant = new TournamentParticipant();
            $save_tournament_participant->tournament_id = '1';
            $save_tournament_participant->time = '00:00';
            $save_tournament_participant->group_id = trim($group->id);
            $save_tournament_participant->member_id = trim($request->input('member_id'));
            $save_tournament_participant->slug = Carbon::now()->unix();
            $save_tournament_participant->save();

            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.team_created'));
            return redirect()->to('tournament/' . $tournament_slug . '/group/' . $group_slug);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
