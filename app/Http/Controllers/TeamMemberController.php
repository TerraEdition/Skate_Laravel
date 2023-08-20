<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    public function create($team)
    {
        try {
            # check team exist
            $team = Team::where('slug', $team)->first();
            # check if validation fails
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            $data = [
                'slug' => $team,
            ];
            return view('Dashboard.Team.Member.Create', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function store(Request $request, $slug)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'member' => ['required', Rule::unique('team_members', 'member')],
                'address' => 'nullable',
                'birth' => 'required|date_format:Y-m-d',
                'email' => 'nullable|email',
                'phone' => 'nullable|integer',
                'gender' => 'required|in:1,2',
                'image' => 'nullable|image',
            ], [], [
                'member' => 'Nama Aggota',
                'address' => 'Alamat',
                'birth' => 'Tanggal Lahir',
                'email' => 'Email',
                'phone' => 'No HP',
                'gender' => 'Jenis Kelamin',
                'image' => 'Foto',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $team = Team::where('slug', $slug)->first();
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            DB::beginTransaction();
            if ($request->file('image')) {
                $image = Carbon::now()->unix() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/image/teams/member', $image);
            }
            $save_member = new TeamMember();
            $save_member->team_id = $team->id;
            $save_member->member = trim($request->input('member'));
            $save_member->birth = trim($request->input('birth'));
            $save_member->email = trim($request->input('email'));
            $save_member->address = trim($request->input('address'));
            $save_member->phone = trim($request->input('phone'));
            $save_member->gender = trim($request->input('gender'));
            $save_member->image = $image ?? 'default.png';
            $save_member->save();
            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.member_team_created'));
            return redirect()->to('team/' . $slug);
        } catch (\Throwable $th) {
            DB::rollback();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
