<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Transformers\Json;
use Illuminate\Http\Request;
use App\PasswordReset;
use App\Notifications\MailResetPasswordNotification;
use App\Notifications\PasswordResetSuccess;

class PasswordController extends Controller
{
    public function reset(Request $request)
    {
        $user = User::find($request->input('id'));
        $user->password = Hash::make($request->input('password'));

        if ($user->save()) {
            $user->notify(new PasswordResetSuccess());
            return response()->json(Json::response(null, 'Successfully updated password!'), 200);
        } else {
            return response()->json(null, 401);
        }
    }

    public function current(Request $request)
    {
        $hashedPassword = User::find($request->input('id'))->password;
        return response()->json(Json::response(
            Hash::check($request->input('password'), $hashedPassword), 
            null
        ), 200);
    }

    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = str_random(60);

        $passwordReset = PasswordReset::firstOrCreate(
            ['email' => $user->email],
            ['email' => $user->email,'token' => $token]
        );

        $user->notify(new MailResetPasswordNotification($token));
       
        if($user && $passwordReset){
            return response()->json(Json::response(null, 'Link to reset password sent to email'), 200);
        } else {
			return response()->json(null, 401);
        }
    }

    public function validateReminderToken(Request $request)
    {
        $token = PasswordReset::where('token',$request->token)
                            ->where('created_at','>',Carbon::now()->subHours(2))
                            ->first();

        $user = User::whereEmail($token->email)->first();
        if($token && $user){
            return response()->json(Json::response(compact('user'), null), 200);
        } else {
            return response()->json(null, 401);  
        }        
    }
}
