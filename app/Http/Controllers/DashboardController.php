<?php

namespace App\Http\Controllers;

use App\Models\Dashboard\ModuleDashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
        ];
        return view('Dashboard.Welcome', $data);
    }
}
