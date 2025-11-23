<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }
}
