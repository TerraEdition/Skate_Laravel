<?php

namespace App\Http\Controllers;

use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        try {
            $status = $request->get('status') ?? 'now';
            $data = [
                'status' => $status,
                'data' => TournamentGroup::get_all($status),
            ];
            return view('Dashboard.Participant.Index', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail($tournament_slug, $group_slug)
    {
        $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
        if (empty($group)) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', __("global.tournament_group_not_found"));
            return redirect()->back();
        }
        try {
            $data = [
                'group' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group_slug, ($group->status == 2 ? true : false)),
            ];
            return view('Dashboard.Participant.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function competition($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __("global.tournament_group_not_found"));
                return redirect()->back();
            } else {
                if ($group->status == 0) {
                    # set start tournament group
                    $group->status = 1;
                    $group->update();
                } else if ($group->status == 2) {
                    Session::flash('bg', 'alert-danger');
                    Session::flash('message', __('global.tournament_group_is_finished'));
                    return redirect()->back();
                }
            }
            $data = [
                'group' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group_slug),
            ];
            return view('Dashboard.Participant.Competition', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function close_competition($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __("global.tournament_group_not_found"));
                return redirect()->back();
            }

            # set finish tournament group
            $group->status = 2;
            $group->update();

            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.group_updated'));
            return redirect()->to('participant/' . $tournament_slug . '/' . $group_slug);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
