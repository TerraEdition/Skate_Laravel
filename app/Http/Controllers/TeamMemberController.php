<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use App\Rules\unique_slug;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TournamentParticipant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    public function create($team_slug)
    {
        try {
            # check team exist
            $team = Team::where('slug', $team_slug)->first();
            # check if validation fails
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            $data = [
                'slug' => $team_slug,
            ];
            return view('Dashboard.Team.Member.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function store(Request $request, $team_slug)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'member' => ['required', Rule::unique('team_members', 'member')],
                'address' => 'nullable',
                'birth' => 'required|date_format:Y',
                'email' => 'nullable|email',
                'phone' => 'nullable|numeric',
                'gender' => 'required|in:1,2',
                'image' => 'nullable|image',
            ], [], [
                'member' => 'Nama Aggota',
                'address' => 'Alamat',
                'birth' => 'Tahun Lahir',
                'email' => 'Email',
                'phone' => 'No HP',
                'gender' => 'Jenis Kelamin',
                'image' => 'Foto',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $team = Team::where('slug', $team_slug)->first();
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            DB::beginTransaction();
            if ($request->file('image')) {
                $image_name = Carbon::now()->unix() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/image/teams/member', $image_name);
            }
            $save_member = new TeamMember();
            $save_member->team_id = $team->id;
            $save_member->member = trim($request->input('member'));
            $save_member->birth = trim($request->input('birth'));
            $save_member->email = trim($request->input('email'));
            $save_member->address = trim($request->input('address'));
            $save_member->phone = trim($request->input('phone'));
            $save_member->gender = trim($request->input('gender'));
            $save_member->image = $image_name ?? 'default.png';
            $save_member->save();
            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.member_team_created'));
            return redirect()->to('team/' . $team_slug);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail($team_slug, $member_slug)
    {
        try {
            # check team exist
            $team = Team::where('slug', $team_slug)->first();
            # check if validation fails
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            # check team exist
            $member = TeamMember::where('slug', $member_slug)->first();
            # check if validation fails
            if (empty($member)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.member_team_not_found'));
                return redirect()->back();
            }
            $data = [
                'team_slug' => $team_slug,
                'data' => $member,
                'tournament_incoming' => TournamentParticipant::get_tournament_by_member_slug($member_slug),
            ];
            return view('Dashboard.Team.Member.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function edit($team_slug, $member_slug)
    {
        try {
            # check team exist
            $member = TeamMember::where('slug', $member_slug)->first();
            # check if validation fails
            if (empty($member)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            $data = [
                'team_slug' => $team_slug,
                'data' => $member,
            ];
            return view('Dashboard.Team.Member.Edit', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function put(Request $request, $team_slug, $member_slug)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'member' => ['required', new unique_slug('team_members', 'member', $member_slug)],
                'address' => 'nullable',
                'birth' => 'required|date_format:Y',
                'email' => 'nullable|email',
                'phone' => 'nullable|numeric',
                'gender' => 'required|in:1,2',
                'image' => 'nullable|image',
            ], [], [
                'member' => 'Nama Aggota',
                'address' => 'Alamat',
                'birth' => 'Tahun Lahir',
                'email' => 'Email',
                'phone' => 'No HP',
                'gender' => 'Jenis Kelamin',
                'image' => 'Foto',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $team = Team::where('slug', $team_slug)->first();
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            DB::beginTransaction();
            if ($request->file('image')) {
                $image_name = Carbon::now()->unix() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/image/teams/member', $image_name);
            }
            $save_member = TeamMember::where('slug', $member_slug)->first();
            $save_member->team_id = $team->id;
            $save_member->member = trim($request->input('member'));
            $save_member->birth = trim($request->input('birth'));
            $save_member->email = trim($request->input('email'));
            $save_member->address = trim($request->input('address'));
            $save_member->phone = trim($request->input('phone'));
            $save_member->gender = trim($request->input('gender'));
            $save_member->image = $image_name ?? $save_member->image;
            $save_member->save();
            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.member_team_created'));
            return redirect()->to('team/' . $team_slug . '/member/' . $member_slug);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
