<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
        $this->middleware('admin');
    }

    public function admin()
    {
        return view('admin.index');
    }
}
