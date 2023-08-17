<?php

namespace App\Http\Controllers;

use App\Helpers\Format;
use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'location' => 'required',
                'description' => 'nullable',
            ], [
                'end_time.after' => 'Waktu Selesai harus lebih besar dari waktu mulai'
            ], [
                'tournament' => 'Turnamen',
                'start_date' => 'Tanggal Mulai',
                'end_date' => 'Tanggal Selesai',
                'start_time' => 'Waktu Mulai',
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
            $save_tournament->start_time = trim($request->input('start_time'));
            $save_tournament->end_time = trim($request->input('end_time'));
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

    public function detail($slug)
    {
        try {
            $data = [
                'date' => Tournament::get_detail_by_slug($slug),
            ];
            return view('Dashboard.Tournament.Detail', $data);
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
