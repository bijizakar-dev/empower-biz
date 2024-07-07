<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function getDashboard()
    {
        return view('home');
    }
}
