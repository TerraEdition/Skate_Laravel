<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;

class HomeController extends Controller
{
    public function index(){
        $data =[
            'tournament_incoming'=>Tournament::get_near_tournament()
        ];
        return view('Home.Index', $data);
    }
}
