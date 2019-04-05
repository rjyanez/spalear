<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Transformers\Json;

class NotificationController extends Controller
{
    
    public function unread($id)
    {
        $user = User::find($id);
        $notifications = $user->unreadNotifications;

        return response()->json(Json::response(compact('notifications')), 200);
    }

    public function markAsRead($id)
    {
        $user = User::find($id);
        if($user->unreadNotifications->markAsRead()){            
            return response()->json(Json::response(compact('notifications')), 200);
        } else {
            return response()->json(null, 401);
        }
    }   
}
