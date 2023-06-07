<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::paginate(10);
        $page = request()->query('page');
        return view('home', compact('page'));
    }
}
