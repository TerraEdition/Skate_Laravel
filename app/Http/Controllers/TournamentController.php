<?php

namespace App\Http\Controllers;

use App\Excel\Tournament as ExcelTournament;
use App\Helpers\Format;
use App\Models\Tournament;
use App\Models\TournamentGallery;
use App\Models\TournamentGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = [
                'data' => Tournament::get_all($request),
            ];
            return view('Dashboard.Tournament.Index', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function create()
    {
        try {
            return view('Dashboard.Tournament.Create');
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
                'tournament' => ['required', Rule::unique('tournaments', 'tournament')],
                'start_date' => 'required|date_format:Y-m-d|after:' . date('Y-m-d'),
                'end_date' => 'required|date_format:Y-m-d|after:' . $request->input('start_date'),
                'location' => 'required',
                'description' => 'nullable',
            ], [
                'end_time.after' => 'Waktu Selesai harus lebih besar dari waktu mulai'
            ], [
                'tournament' => 'Turnamen',
                'start_date' => 'Tanggal Mulai',
                'end_time' => 'Waktu Selesai',
                'location' => 'Lokasi',
                'description' => 'deskripsi',
            ]);

            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $save_tournament = new Tournament();
            $save_tournament->tournament = trim($request->input('tournament'));
            $save_tournament->location = trim($request->input('location'));
            $save_tournament->description = Format::clean(trim($request->input('description')));
            $save_tournament->start_date = trim($request->input('start_date'));
            $save_tournament->end_date = trim($request->input('end_date'));
            $save_tournament->save();

            DB::commit();
            Session::flash('bg', 'alert-success');
            Session::flash('message', __('global.tournament_created'));
            return redirect()->to('tournament/' . $save_tournament->slug);
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }

    public function detail(Request $request, $tournament_slug)
    {
        try {
            $data = [
                'data' => Tournament::get_detail_by_slug($tournament_slug),
                'group' => TournamentGroup::get_by_tournament_slug($request, $tournament_slug)
            ];
            if (empty($data['data'])) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_not_found'));
                return redirect()->back();
            }
            return view('Dashboard.Tournament.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function edit($tournament_slug)
    {
        try {
            $data = [
                'data' => Tournament::get_detail_by_slug($tournament_slug),
            ];

            if (empty($data['data'])) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_not_found'));
                return redirect()->back();
            }

            return view('Dashboard.Tournament.Edit', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function export_tournament($tournament_slug, $team_slug = '')
    {
        ExcelTournament::export_excel($tournament_slug, $team_slug);
    }

    public function gallery($tournament_slug)
    {
        try {
            $data = [
                'tournament' => Tournament::get_detail_by_slug($tournament_slug),
                'gallery' => TournamentGallery::get_by_tournament_slug($tournament_slug),
            ];

            if (empty($data['tournament'])) {
                Session::flash('bg', 'alert-danger');
                Session::flash('message', __('global.tournament_not_found'));
                return redirect()->back();
            }

            return view('Dashboard.Tournament.Gallery.Index', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
    public function store_gallery(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file',
            ], [], [
                'file' => 'Berkas',
            ]);
            # check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
