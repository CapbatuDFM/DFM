<?php

namespace App\Http\Controllers;

use App\Services\DashboardSummary;

class DashboardController extends Controller
{
    public function __invoke(DashboardSummary $summary)
    {
        return view('dashboard', $summary->get());
    }
}
