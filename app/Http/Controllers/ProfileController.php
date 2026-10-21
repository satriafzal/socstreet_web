<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the Settings page with two cards & bottom legal accordion.
     */
    public function settings(): View
    {
        $user = [
            'username' => 'Player_One',
            'created_at' => '14 Oktober 2026',
            'total_bookings' => 4,
        ];

        return view('pages.profile.settings', compact('user'));
    }
}

