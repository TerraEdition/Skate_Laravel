<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class TeamController extends Controller
{
    public function index()
    {
        try {
            return view('Dashboard.Team.Index');
        } catch (\Throwable $th) {
            throw $th->getMessage();
        }
    }
}
