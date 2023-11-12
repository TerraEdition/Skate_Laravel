<?php

namespace App\Http\Controllers;

use App\Models\ParticipantTournamentDetail;
use App\Models\SettingGroupRound;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingGroupRoundController extends Controller
{
    public function create($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __("global.tournament_group_not_found"));
                return redirect()->back();
            }
            $step = 1;
            # check step create
            if (Session::has('setting_group_' . $group_slug)) {
                $step = Session::get('setting_group_' . $group_slug);
            } else {
                Session::put('setting_group_' . $group_slug, 1);
            }
            $data = [
                'group' => $group,
                'step' => $step,
                'tournament_slug' => $tournament_slug,
                'data' => Session::get('data_setting_group_' . $group_slug) ?? [],
                'participant' => TournamentParticipant::get_all_participant_by_group_slug($group_slug),
            ];
            return view('Dashboard.SettingGroup.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function store(Request $request, $tournament_slug, $group_slug)
    {
        try {
            # check step create
            $step = Session::get('setting_group_' . $group_slug);
            if ($step != 2) {
                $validator = Validator::make($request->all(), [
                    'total_seat' => 'required|numeric',
                ], [], [
                    'total_seat' => 'Total Seat',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'seat' => 'required|array',
                ], [], [
                    'seat' => 'Seat',
                ]);
            }
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            if ($step != 2) {
                if (Session::has('data_setting_group_' . $group_slug)) {
                    $total = end(Session::get('data_setting_group_' . $group_slug)['total_seat'])['participant_left'];
                } else {
                    $total = TournamentParticipant::get_all_participant_by_group_slug($group_slug)->count();
                }
                if (($request->input('total_seat') * 2) > $total) {
                    Session::flash('bg', 'alert-danger');
                    Session::flash('message', 'Total Peserta harus memiliki 2x dari total seat yang di buat');
                    return redirect()->back();
                }
                Session::put('data_setting_group_' . $group_slug, [
                    'total_seat' => [
                        [
                        'round' => 1,
                        'group_id'=>TournamentGroup::where("slug",$group_slug)->first()->id,
                        'data' => $request->input('total_seat'),
                        'passes_position' => $request->input('passes_position'),
                        'participant_left' => $request->input('passes_position') * $request->input('total_seat'),
                        ]
                    ],
                ]);
            } else {
                Session::put(
                    'data_setting_group_' . $group_slug,
                    array_merge(
                        Session::get('data_setting_group_' . $group_slug),
                        [
                            'seat' => [
                                'round' => 1,
                                'data' => $request->input('seat'),
                            ]
                        ]
                    )
                );
                    # selesai
                    $this->_final_store($group_slug);
                    return redirect()->to('participant/' . $tournament_slug . '/' . $group_slug);
            }
            Session::put('setting_group_' . $group_slug, $step + 1);
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function cancel($tournament_slug, $group_slug)
    {
        try {
            # reset data & step
            Session::forget('setting_group_' . $group_slug);
            Session::forget('data_setting_group_' . $group_slug);
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function _final_store($group_slug)
    {
        $group = TournamentGroup::select('id')->where('slug', $group_slug)->first();
        if (empty($group)) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', __("global.group_not_found"));
            return redirect()->back();
        }
        $data = Session::get('data_setting_group_' . $group_slug);
        # save rule group
        foreach($data['total_seat'] as $rule){
                $save_rule =new SettingGroupRound();
                $save_rule->group_id = $rule['group_id'];
                $save_rule->passes =$rule['passes_position'];
                $save_rule->round = 1;
                $save_rule->save();
        }

        # save group seat
        foreach ($data['seat']['data'] ?? [] as $key => $k) {
            foreach ($k as $r) {
                $save_seat = new ParticipantTournamentDetail();
                $save_seat->participant_id = $r;
                $save_seat->group_id = $group->id;
                $save_seat->seat = $key;
                $save_seat->round = '1';
                $save_seat->save();
            }
        }
        Session::forget('setting_group_' . $group_slug);
        Session::forget('data_setting_group_' . $group_slug);
    }
}
