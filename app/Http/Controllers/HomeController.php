<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;

use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tournament = Tournament::get_near_tournament();
        $data = [
            'tournament' => $tournament,
            'group' => TournamentGroup::get_by_tournament_slug($request, $tournament->slug ?? 'null'),
        ];
        return view('Home.Index', $data);
    }
    public function detail($group_slug)
    {
        $group = TournamentGroup::get_by_group_slug($group_slug);
        if (empty($group)) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', __("global.tournament_group_not_found"));
            return redirect()->back();
        }
        try {
            $data = [
                'group' => $group,
            ];
            return view('Home.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
