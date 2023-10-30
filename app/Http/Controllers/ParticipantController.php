<?php

namespace App\Http\Controllers;

use App\Excel\Participant;
use App\Helpers\Files;
use App\Helpers\Response;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        try {
            $status = $request->get('status') ?? 'now';
            $data = [
                'status' => $status,
                'data' => TournamentGroup::get_all($status),
            ];
            return view('Dashboard.Participant.Index', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail($tournament_slug, $group_slug)
    {
        $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
        if (empty($group)) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', __("global.tournament_group_not_found"));
            return redirect()->back();
        }
        try {
            $data = [
                'group' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group_slug, ($group->status == 2 ? true : false)),
            ];
            return view('Dashboard.Participant.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function competition($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __("global.tournament_group_not_found"));
                return redirect()->back();
            } else {
                if ($group->status == 0) {
                    # set start tournament group
                    $group->status = 1;
                    $group->update();
                } else if ($group->status == 2) {
                    Session::flash('bg', 'alert-danger');
                    Session::flash('message', __('global.tournament_group_is_finished'));
                    return redirect()->back();
                }
            }
            $data = [
                'group' => $group,
                'participant' => TournamentParticipant::get_by_group_slug($group_slug),
            ];
            return view('Dashboard.Participant.Competition', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function close_competition($tournament_slug, $group_slug)
    {
        try {
            $group = TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug);
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __("global.tournament_group_not_found"));
                return redirect()->back();
            }

            # set finish tournament group
            $group->status = 2;
            $group->update();

            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.tournament_group_updated'));
            return redirect()->to('participant/' . $tournament_slug . '/' . $group_slug);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function tournament_screen($tournament_slug, $group_slug)
    {
        try {
            return view('Dashboard.Participant.Screen');
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function mini_screen($tournament_slug, $group_slug)
    {
        try {
            $data = [
                'data' => TournamentGroup::get_by_tournament_slug_by_group_slug($tournament_slug, $group_slug),
                'participant' => TournamentParticipant::get_by_group_slug($group_slug, true)
            ];
            $view = view('Dashboard.Participant.MiniScreen', $data)->render();
            return Response::make(200, __('global.success'), $view);
        } catch (\Throwable $th) {
            return Response::make(500, $th->getMessage() . ' : ' . $th->getLine());
        }
    }
    public function export_excel_participant($tournament_slug, $group_slug = '')
    {
        Participant::export_excel_participant($tournament_slug, $group_slug);
    }
    public function import_excel_participant(Request $request)
    {
        try {
            # check input validation
            $validator = Validator::make($request->all(), [
                'excel' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'group_slug' => 'required',
            ], [], [
                'excel' => 'File Excel',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $group = TournamentParticipant::get_by_group_slug($request->input('group_slug'));
            if (empty($group)) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.group_not_found'));
                return redirect()->back();
            }

            DB::beginTransaction();
            $excel = Carbon::now()->unix() . '.' . $request->file('excel')->extension();
            $path = storage_path('app/public/excel/participant/');
            Files::is_existing($path);
            $request->file('excel')->storeAs('public/excel/participant', $excel);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path . $excel);
            $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $data_error = [];
            foreach ($sheet as $k => $p) {
                if ($k > 4) {
                    $error = false;
                    $update_participant = TournamentParticipant::get_participant_by_name_by_team_by_group_slug($p['C'], $p['D'], $request->input('group_slug'));

                    if (empty($update_participant)) {
                        $error = true;
                        $msg = __('global.participant_not_found');
                    }

                    if (!preg_match('/^\d{2}:\d{2}$/', $p['E'])) {
                        $p['E'] = '00:00';
                    }
                    if (!$error) {
                        $update_participant->no_participant = str_pad($p['B'], 3, '0', STR_PAD_LEFT);
                        $update_participant->time = $p['E'];
                        $update_participant->update();
                    } else {
                        $data_error[] = [
                            'row' => $k,
                            'no_participant' => $p['B'],
                            'member' => $p['C'],
                            'team' => $p['D'],
                            'time' => $p['E'],
                            'msg' => $msg,
                            'group_slug' => $request->input('group_slug'),
                        ];
                    }
                }
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
    public function failed_import_excel_participant($tournament_slug, $group_slug)
    {
        try {
            $data = [
                'data' => cache()->get('data_error'),
            ];
            return view('Dashboard.Participant.Import_Failed', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
