<?php

namespace App\Http\Controllers;

use App\Excel\Participant;
use App\Helpers\Response;
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
            Session::flash('message', __('global.tournament_group_updated'));
            return redirect()->to('participant/' . $tournament_slug . '/' . $group_slug);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function tournament_screen($tournament_slug, $group_slug)
    {
        try {
            return view('Dashboard.Participant.Screen');
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function mini_screen($tournament_slug, $group_slug)
    {
        try {
            $data = [
                'data' => TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug),
                'participant' => TournamentParticipant::get_by_group_slug($group_slug, true)
            ];
            $view = view('Dashboard.Participant.MiniScreen', $data)->render();
            return Response::make(200, __('global.success'), $view);
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
    public function export_excel_participant($tournament_slug, $group_slug = '')
    {
        Participant::export_excel_participant($tournament_slug, $group_slug);
    }
}
