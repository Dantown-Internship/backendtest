<?php

namespace App\Http\Controllers\Logs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class LogsController extends Controller
{
    public function index(){

        $activities = Activity::where('causer_id',auth()->user()->id)->get();

        return view('users.dashboard.logs', compact('activities'));
    }
}
