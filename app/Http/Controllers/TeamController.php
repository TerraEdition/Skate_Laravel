<?php

namespace App\Http\Controllers;

use App\Models\ContactPerson;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Rules\unique_slug;
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
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function create()
    {
        try {
            return view('Dashboard.Team.Create');
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
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
                'web' => 'nullable|active_url',
                'email' => 'required|email',
                'image' => 'nullable|image',
                'contact' => 'nullable|array',
                'contact.*.name' => 'required',
                'contact.*.phone' => 'required|numeric',
            ], [], [
                'team' => 'Tim',
                'coach' => 'Pelatih',
                'web' => 'URL Website',
                'address' => 'Alamat',
                'email' => 'Email',
                'image' => 'Logo',
                'contact' => 'Kontak',
                'contact.*.name' => 'Nama Kontak',
                'contact.*.phone' => 'No HP',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();
            if ($request->file('image')) {
                $logo_name = Carbon::now()->unix() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/image/teams', $logo_name);
            }

            $save_team = new Team();
            $save_team->team = trim($request->input('team'));
            $save_team->address = trim($request->input('address'));
            $save_team->email = trim($request->input('email'));
            $save_team->website = trim($request->input('web'));
            $save_team->coach = trim($request->input('coach'));
            $save_team->image = $logo_name ?? 'default.png';
            $save_team->save();

            foreach ($request->input('contact') as $contact) {
                $save_cp = new ContactPerson();
                $save_cp->team_id = $save_team->id;
                $save_cp->name = trim($contact['name']);
                $save_cp->phone = trim($contact['phone']);
                $save_cp->save();
            }

            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.team_created'));
            return redirect()->to('team/' . $save_team->slug);
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function detail(Request $request, $slug)
    {
        try {
            $data = [
                'data' => Team::get_detail_by_slug($slug),
                'member' => TeamMember::get_by_team_slug($request, $slug),
                'near_tournament' => Tournament::get_near_tournament(),
            ];
            if (empty($data['data'])) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            return view('Dashboard.Team.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function edit($slug)
    {
        try {
            $team = Team::get_detail_by_slug($slug);
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            $data = [
                'data' => $team,
                'contact' => ContactPerson::where("team_id", $team->id)->get()
            ];
            return view('Dashboard.Team.Edit', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function put(Request $request, $team_slug)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'team' => ['required', new unique_slug('teams', 'team', $team_slug)],
                'coach' => 'required|max:100',
                'address' => 'required',
                'web' => 'nullable|active_url',
                'email' => 'required|email',
                'image' => 'nullable|image',
                'contact' => 'nullable|array',
                'contact.*.name' => 'required',
                'contact.*.phone' => 'required|numeric',
            ], [], [
                'team' => 'Tim',
                'coach' => 'Pelatih',
                'web' => 'URL Website',
                'address' => 'Alamat',
                'email' => 'Email',
                'image' => 'Logo',
                'contact' => 'Kontak',
                'contact.*.name' => 'Nama Kontak',
                'contact.*.phone' => 'No HP',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            DB::beginTransaction();
            if ($request->file('image')) {
                $logo_name = Carbon::now()->unix() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/image/teams', $logo_name);
            }

            $save_team = Team::where('slug', $team_slug)->first();
            if (empty($save_team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }
            $save_team->team = trim($request->input('team'));
            $save_team->address = trim($request->input('address'));
            $save_team->email = trim($request->input('email'));
            $save_team->website = trim($request->input('web'));
            $save_team->coach = trim($request->input('coach'));
            $save_team->image = $logo_name ?? $save_team->image;
            $save_team->save();

            $contact_exist = [];
            foreach ($request->input('contact') as $contact) {
                $check_contact = ContactPerson::select('id')->where('name', $contact['name'])->where('phone', $contact['phone'])->where('team_id', $save_team->id)->first();
                if (empty($check_contact)) {
                    $save_cp = new ContactPerson();
                    $save_cp->team_id = $save_team->id;
                    $save_cp->name = trim($contact['name']);
                    $save_cp->phone = trim($contact['phone']);
                    $save_cp->save();
                } else {
                    $contact_exist[] = $check_contact->id;
                }
            }
            # delete contact
            if (!empty($contact_exist)) {
                ContactPerson::where('team_id', $save_team->id)->whereNotIn('id', $contact_exist)->delete();
            }
            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.team_created'));
            return redirect()->to('team/' . $save_team->slug);
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function import_excel()
    {
    }
}
