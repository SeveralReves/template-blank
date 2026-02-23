<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {   
        $users = User::all();

        return view('dashboard.index', compact('users'));
    }
    public function users(Request $request)
    {   
        $users = User::all();

        return view('dashboard.users', compact('users'));
    }
}
