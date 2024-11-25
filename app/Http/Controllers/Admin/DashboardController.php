<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class DashboardController extends Controller
{

    public function index()
    {
        $authUser = Auth::user();
        $totalEvents = Event::where('created_by',$authUser->id)->count();
        $todaysEvents = Event::where('date',date('Y-m-d'))->where('created_by',$authUser->id)->count();
        $cancelEvents = Event::where('status',2)->where('created_by',$authUser->id)->count();
        return view('admin.dashboard')->with(['totalEvents' => $totalEvents,'todaysEvents' => $todaysEvents,'cancelEvents'=>$cancelEvents]);
    }

    public function changeThemeMode()
    {
        $mode = request()->cookie('theme-mode');

        if($mode == 'dark')
            Cookie::queue('theme-mode', 'light', 43800);
        else
            Cookie::queue('theme-mode', 'dark', 43800);

        return true;
    }
}
