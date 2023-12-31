<?php

namespace App\Http\Controllers;

use App\Excel\Dashboard as ExcelDashboard;
use App\Models\Dashboard\ModuleDashboard;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Helpers\Excel;
use App\Models\SettingGroupRound;
use App\Models\TeamMember;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Carbon\Carbon;
use App\Helpers\Files;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];
        return view('Dashboard.Welcome', $data);
    }
    public function export_excel_by_pass()
    {
        ExcelDashboard::export_excel_by_pass();
    }
    private function _generate_initial(string $name): string
    {
        $word = explode(' ', $name);
        $initial = '';
        foreach ($word as $k) {
            $initial .= strtoupper(substr($k, 0, 1));
        }

        return $initial;
    }

    public function import_excel_by_pass(Request $request)
    {
        try {
            # check input validation
            // |mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
            $validator = Validator::make($request->all(), [
                'excel' => 'required|file',
            ], [], [
                'excel' => 'File Excel',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }


            DB::beginTransaction();
            $excel = Carbon::now()->unix() . '.' . $request->file('excel')->extension();
            $path = storage_path('app/excel/participant/');
            Files::is_existing($path);
            $request->file('excel')->storeAs('excel/participant', $excel);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path . $excel);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $data_error = [];
            $group = TournamentGroup::get_all_tournament_closest();
            foreach ($sheet as $k => $p) {
                if ($k > 6) {
                    foreach ($group as $i => $g) {
                        if ($p[Excel::number_to_alphabet(($i + 1) + Excel::alphabet_to_number("D"))] == 'v') {
                            $error = false;
                            # check group is close or not because already setting group seat before
                            $is_close = SettingGroupRound::where('group_id', $g->group_id)->first();
                            if (empty($is_close)) {
                                $update_participant = TournamentParticipant::get_participant_by_name_by_team_by_group_slug($p['A'], $p['B'], $g->slug);
                                if (empty($update_participant)) {
                                    # save member
                                    # check team is exist
                                    $team = Team::get_by_team_name($p['B']);
                                    if (empty($team)) {
                                        $team = new Team();
                                        $team->team = ucwords(strtolower($p['B']));
                                        $team->coach = '';
                                        $team->website = '';
                                        $team->address = '';
                                        $team->email = '';
                                        $team->team_initial = $p['C'] == '' ? $this->_generate_initial($p['B']) : strtoupper($p['C']);
                                        $team->save();
                                    }
                                    $member = TeamMember::get_id_by_member_name_by_team_slug($p['A'], $request->input('team_slug'));
                                    if (empty($member)) {
                                        # new member
                                        $member = new TeamMember();
                                        $member->member = ucwords(strtolower($p['A']));
                                        $member->gender = $g->gender;
                                        $member->birth = '0';
                                        $member->team_id = $team->id;
                                        $member->save();
                                    }
                                    $check = TournamentParticipant::where('member_id', $member->id)
                                        ->where('group_id', $g->group_id)->first();
                                    if (empty($check)) {
                                        # check already participant
                                        $save = new TournamentParticipant();
                                        $save->no_participant = str_pad($p['D'], 3, '0', STR_PAD_LEFT);
                                        $save->group_id =  $g->group_id;
                                        $save->member_id =  $member->id;
                                        $save->slug =  Carbon::now()->unix() + $k;
                                        $save->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
            cache()->delete('data_error');
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.import_successfull'));
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
