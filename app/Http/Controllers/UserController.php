<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function index()
    {
        try {
            return view('Dashboard.User.Index');
        } catch (\Throwable $th) {
            Session::flash('bg', 'alert-danger');
            Session::flash('message', $th->getMessage() . ':' . $th->getLine());
            return redirect()->back();
        }
    }
}
