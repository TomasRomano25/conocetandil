<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLugares = Lugar::count();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('totalLugares', 'totalUsers'));
    }
}
