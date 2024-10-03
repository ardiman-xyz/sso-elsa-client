<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    
    public function index()
    {

        $ssoClientId = env("SSO_CLIENT_ID");
        $ssoClientSecret = env("SSO_CLIENT_SECRET");
        $ssoUrl = env("SSO_URL");

        $url = "$ssoUrl/auth/authorize?client_id=$ssoClientId&client_secret=$ssoClientSecret";

        return Inertia::render('Welcome', [
            'ssoUrl' => $url
        ]);
    }

}
