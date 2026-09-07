<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('dashboard');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function admin()
    {
        return view('admin');
    }
}
