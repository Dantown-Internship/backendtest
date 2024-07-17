<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function update(string $id){
        $userNotificatioon = auth()->user()->notifications->where('id', $id)->first();
        $userNotificatioon->markAsRead();
        return redirect()->back();
    }
    
}
