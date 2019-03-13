<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Transformers\Json;
use App\User;

class TeacherController extends Controller
{
    public function list(){
        $teachers = User::where('rol_code', 'TE')
                      ->with(['rol','timeZone','country'])
                      ->get()
                      ->map(function ($item) {
                        return [
                          'id' => $item->id,
                          'name' => $item->name, 
                          'email' => $item->email,
                          'avatar' => $item->avatar,
                          'rol' => $item->rol->name,
                          'country' => $item->country->name, 
                          'timeZone' => $item->timeZone->name
                        ];
                      })->toJson();  
        return response()->json(Json::response(compact('teachers')), 200);
    }
}
