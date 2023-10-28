<?php

namespace App\Http\Controllers;

use App\Helpers\Convert;
use App\Helpers\Date;
use App\Helpers\Files;
use App\Models\ContactPerson;
use App\Models\Team;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
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

    public function import_excel(Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'excel' => 'file|mimes:xlsx',
                'team_slug' => 'required',
            ], [], [
                'excel' => 'File Excel',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $team = Team::select('id')->where('slug', $request->input('team_slug'))->first();
            if (empty($team)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.team_not_found'));
                return redirect()->back();
            }

            DB::beginTransaction();
            $excel = Carbon::now()->unix() . '.' . $request->file('excel')->extension();
            $path = storage_path('app/public/excel/join_tournament/');
            Files::is_existing($path);
            $request->file('excel')->storeAs('public/excel/join_tournament', $excel);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path . $excel);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheet2 = $spreadsheet->getSheet(1)->toArray(null, true, true, true);
            dump($sheet);
            dd($sheet2);
            $group = TournamentGroup::get_all_by_tournament_name('incoming', $sheet2[1]['C']);
            $data_error = [];
            $data_save = [];
            foreach ($sheet as $k => $p) {
                if ($k > 6) {
                    $error = false;
                    if (!Date::is_format_excel($p['B'])) {
                        # check format date is d-m-Y
                        $error = true;
                    } else {
                        $member_id = TeamMember::get_id_by_member_name_by_team_slug($p['A'], $request->input('team_slug'));
                        $age = Date::calculate_age($p['B']);
                        $gender = Convert::gender_short($p['C']);
                        if (empty($member_id)) {
                            # new member
                            $member_id = new TeamMember();
                            $member_id->member = $p['A'];
                            $member_id->gender = $p['B'];
                            $member_id->birth = $p['C'];
                            $member_id->team_id = $team->id;
                            $member_id->save();
                        }
                        # check rule group tournament like age, gender
                        foreach ($group as $i => $g) {
                            if ($p[($i + 1) + "C"] == '1') {
                                # check age
                                if ($age < $g->min_age || $age > $g->max_age) {
                                    $error = true;
                                }
                                # check gender
                                if (in_array($g->gender, ['1', '2'])) {
                                    # boy or girl
                                    if ($gender != $g->gender) {
                                        $error = true;
                                    }
                                }
                                if (!$error) {
                                    # check already participant
                                    $check = TournamentParticipant::where('member_id', $member_id->id)->where('group_id', $g->group_id)->first();
                                    if (empty($check)) {
                                        $data_save[] = [
                                            'time' => '00:00',
                                            'group_id' => $g->group_id,
                                            'member_id' => $member_id->id,
                                            'slug' => Carbon::now()->unix() + $i,
                                        ];
                                    }
                                } else {
                                    # requirements not met
                                    $data_error[] = [
                                        'time' => '00:00',
                                        'group_id' => $g->group_id,
                                        'member_id' => $member_id->id,
                                        'slug' => Carbon::now()->unix() + $i,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            if (!empty($data_save)) {
                # save participant
                TournamentParticipant::insert($data_save);
            }
            if (!empty($data_error)) {
                # show error handle

            }
            DB::commit();
        } catch (\Throwable $th) {
            dd("ERROR");
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
