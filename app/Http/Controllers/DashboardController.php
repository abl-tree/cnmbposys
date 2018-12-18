<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('admin.dashboard.index');
    }
}
