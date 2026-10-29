<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn') && !$this->request->getGet('guest')) {
            return redirect()->to(site_url('login'));
        }
        return view('dashboard', [
            'userName' => $session->get('userName') ?: 'Akang Dadang',
            'userRole' => $session->get('userRole') ?: 'Verifikator',
        ]);
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = trim((string) $this->request->getPost('password'));

        if ($username === '' || $password === '') {
            return redirect()->to('/login')->with('error', 'Silakan masukkan NIP / Username dan Password.');
        }

        if ($username !== 'admin' || $password !== '123') {
            return redirect()->to(site_url('login'))->with('error', 'NIP / Username atau Password salah.');
        }

        session()->set([
            'isLoggedIn' => true,
            'userName'   => 'Admin Lab',
            'userNip'    => 'admin',
            'userRole'   => 'Administrator',
        ]);

        return redirect()->to(site_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
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
