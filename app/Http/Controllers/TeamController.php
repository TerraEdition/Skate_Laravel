<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                'data' => Team::get_all($request),
            ];
            return view('Dashboard.Team.Index', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage());
            return redirect()->back();
        }
    }
    public function create()
    {
        try {
            return view('Dashboard.Team.Create');
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage());
            return redirect()->back();
        }
    }
    public function store(Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'team' => ['required', Rule::unique('teams', 'team')],
                'coach' => 'required|max:100',
                'address' => 'required',
                'phone' => 'required|integer',
                'email' => 'required|email',
                'image' => 'nullable|image',
            ], [], [
                'team' => 'Tim',
                'coach' => 'Pelatih',
                'address' => 'Alamat',
                'phone' => 'No HP',
                'email' => 'Email',
                'image' => 'Logo',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $logo_name = Carbon::now()->unix() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('public/image/teams', $logo_name);

            $save_team = new Team();
            $save_team->team = trim($request->input('team'));
            $save_team->address = trim($request->input('address'));
            $save_team->phone = trim($request->input('phone'));
            $save_team->email = trim($request->input('email'));
            $save_team->coach = trim($request->input('coach'));
            $save_team->image = $logo_name;
            $save_team->save();

            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.team_created'));
            return redirect()->to('team/' . $save_team->slug);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage());
            return redirect()->back();
        }
    }
    public function detail($slug)
    {
        try {
            $data = [
                'data' => Team::where('slug', $slug)->first(),
            ];
            if (empty($data['data'])) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            return view('Dashboard.Team.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage());
            return redirect()->back();
        }
    }
}
