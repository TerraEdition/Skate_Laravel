<?php

namespace App\Http\Controllers;

use App\Helpers\Format;
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
                'max_participant' => 'required|integer',
                'max_participant_per_team' => 'required|integer|gt:0|lte:' . $request->input('max_participant') ?? 1,
                'min_age' => 'required|integer|gt:0',
                'max_age' => 'required|integer|gte:' . $request->input('min_age') ?? 1,
                'description' => 'nullable',
            ], [], [
                'group' => 'Group',
                'category' => 'Kategori',
                'max_participant' => 'Total semua peserta',
                'max_participant_per_team' => 'Total peserta per tim',
                'description' => 'Deskripsi',
                'min_age' => 'Minimal umur peserta',
                'max_age' => 'Maksimal umur peserta',
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
            $save_group->max_participant = trim($request->input('max_participant'));
            $save_group->max_per_team = trim($request->input('max_participant_per_team'));
            $save_group->description = Format::clean(trim($request->input('description')));
            $save_group->min_age = trim($request->input('min_age'));
            $save_group->max_age = trim($request->input('max_age'));
            $save_group->gender = trim($gender);
            $save_group->save();

            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.tournament_created'));
            return redirect()->to('tournament/' . $tournament_slug);
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail($tournament_slug, $slug)
    {
        try {
            $group = TournamentGroup::get_by_slug($slug);
            $total_participant = TournamentParticipant::total_participant();

            $can_add_participant = !($total_participant >= $group->max_participant);

            $data = [
                'tournament_slug' => $tournament_slug,
                'data' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group->slug),
                'can_add_participant' => $can_add_participant,
            ];
            return view('Dashboard.Tournament.Group.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
