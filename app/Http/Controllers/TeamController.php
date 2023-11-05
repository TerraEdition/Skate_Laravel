<?php

namespace App\Http\Controllers;

use App\Helpers\Convert;
use App\Helpers\Date;
use App\Helpers\Excel;
use App\Helpers\Files;
use App\Models\ContactPerson;
use App\Models\SettingGroupRound;
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
                'team_initial' => ['required', 'max:5', Rule::unique('teams', 'team_initial')],
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
                'team_initial' => 'Kode Tim',
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
            $save_team->team_initial = trim($request->input('team_initial'));
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
                'team_initial' => ['required', 'max:5', new unique_slug('teams', 'team_initial', $team_slug)],
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
                'team_initial' => 'Kode Tim',
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
            $save_team->team_initial = trim($request->input('team_initial'));
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
            // mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
            $validator = Validator::make($request->all(), [
                'excel' => 'required|file',
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
            $request->file('excel')->storeAs('excel/join_tournament', $excel);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path . $excel);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheet2 = $spreadsheet->getSheet(1)->toArray(null, true, true, true);

            $group = TournamentGroup::get_all_by_tournament_name('incoming', $sheet2[1]['C']);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.group_not_found'));
                return redirect()->back();
            }

            $data_error = [];
            $data_save = [];
            foreach ($sheet as $k => $p) {
                if ($k > 6) {
                    $error = false;
                    if (($p['B'] <= 1990) || ($p['B'] >= date("Y"))) {
                        # check format date is d-m-Y
                        Session::flash('bg', 'alert-danger');
                        Session::flash('message', "Kesalahan Format tanggal lahir di baris : " . $k);
                        return redirect()->back();
                    } else {
                        $member_id = TeamMember::get_id_by_member_name_by_team_slug($p['A'], $request->input('team_slug'));
                        $gender = Convert::gender_short($p['C']);
                        if (empty($member_id)) {
                            # new member
                            $member_id = new TeamMember();
                            $member_id->member = $p['A'];
                            $member_id->gender = $gender;
                            $member_id->birth = $p['B'];
                            $member_id->team_id = $team->id;
                            $member_id->save();
                        } else {
                            $gender = $member_id->gender;
                        }
                        # check rule group tournament like age, gender
                        foreach ($group as $i => $g) {
                            if ($p[Excel::number_to_alphabet(($i + 1) + Excel::alphabet_to_number("C"))] == 'v') {

                                # check age
                                if ($p['B'] > $g->max_age || $p['B'] < $g->min_age) {
                                    $error = true;
                                    $msg = "Tahun Kelahiran tidak memenuhi persyaratan";
                                }
                                # boy or girl
                                if ($gender != $g->gender) {
                                    $error = true;
                                    $msg = "Jenis Kelamin tidak memenuhi persyaratan";
                                }
                                if (!$error) {
                                    # check group is close or not because already setting group seat before
                                    $is_close = SettingGroupRound::where('group_id', $g->group_id)->first();
                                    if (empty($is_close)) {
                                        # check already participant
                                        $check = TournamentParticipant::where('member_id', $member_id->id)
                                            ->where('group_id', $g->group_id)->first();
                                        if (empty($check)) {
                                            $data_save[] = [
                                                'time' => '00:00:000',
                                                'group_id' => $g->group_id,
                                                'member_id' => $member_id->id,
                                                'slug' => Carbon::now()->unix() + $i,
                                            ];
                                        }
                                    }
                                } else {
                                    # requirements not met
                                    $data_error[] = [
                                        'row' => $k,
                                        'time' => '00:00:000',
                                        'group_id' => $g->group_id,
                                        'team_slug' => $member_id->team_slug,
                                        'team' => $member_id->team,
                                        'birth' => $member_id->birth,
                                        'group' => $g->group,
                                        'member_id' => $member_id->id,
                                        'member' => $member_id->member,
                                        'slug' => Carbon::now()->unix() + $i,
                                        'msg' => $msg,
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
            DB::commit();
            if (!empty($data_error)) {
                # show error handle
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.import_failed', ['n' => count($data_error)]));
                cache()->forever('data_error', $data_error);
                return redirect()->to(url()->current() . '/failed');
            } else {
                cache()->delete('data_error');
                Session::flash('bg', 'alert-success');
                Session::flash('message', __('global.import_successfull'));
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function import_excel_failed()
    {
        try {
            $data = [
                'data' => cache()->get('data_error'),
            ];
            return view('Dashboard.Team.Import_Failed', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
