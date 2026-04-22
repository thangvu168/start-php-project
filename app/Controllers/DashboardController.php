<?php

class DashboardController extends Controller
{
    public function showDashboard(): void
    {
        $this->view('dashboard/index', [
            'title' => "Dashboard",
        ]);
    }
}
