<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function login(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Show the registration page.
     */
    public function register(): View
    {
        return view('pages.auth.register');
    }
}

