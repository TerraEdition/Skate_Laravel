<?php

namespace App\Http\Controllers;

use App\Helpers\Format;
use App\Models\SettingGroupRound;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TournamentGroupController extends Controller
{
    public function create($tournament_slug)
    {
        try {
            $data = [
                'tournament_slug' => $tournament_slug,
            ];
            return view('Dashboard.Tournament.Group.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function store($tournament_slug, Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'group' => ['required', Rule::unique('tournament_groups', 'group')],
                'category' => 'required|array',
                'category.*' => 'required|in:1,2',
                'min_age' => 'required|date_format:Y|gt:1990|lt:' . date("Y"),
                'max_age' => 'required|date_format:Y|gte:' . ($request->input('min_age') ?? 1),
                'description' => 'nullable',
            ], [], [
                'group' => 'Group',
                'category' => 'Kategori',
                'description' => 'Deskripsi',
                'min_age' => 'Batas bawah tahun kelahiran',
                'max_age' => 'Batas atas tahun kelahiran',
            ]);

            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            $category = $request->input('category');
            if (count(array_intersect($category, ['1', '2'])) == 2) {
                $gender = 0;
            } else {
                $gender = $category[0];
            }
            $tournament = Tournament::select('id')->where('slug', $tournament_slug)->first();
            $save_group = new TournamentGroup();
            $save_group->tournament_id = $tournament->id;
            $save_group->group = trim($request->input('group'));
            $save_group->description = Format::clean(trim($request->input('description')));
            $save_group->min_age = trim($request->input('min_age'));
            $save_group->max_age = trim($request->input('max_age'));
            $save_group->gender = trim($gender);
            $save_group->save();

            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.tournament_group_created'));
            return redirect()->to('tournament/' . $tournament_slug);
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_group_not_found'));
                return redirect()->back();
            }
            $data = [
                'tournament_slug' => $tournament_slug,
                'data' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group->slug),
                'is_close' => !empty(SettingGroupRound::where('group_id', $group->id)->first()),
            ];
            return view('Dashboard.Tournament.Group.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function edit($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_group_not_found'));
                return redirect()->back();
            }
            $data = [
                'data' => $group,
            ];
            return view('Dashboard.Tournament.Group.Edit', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
