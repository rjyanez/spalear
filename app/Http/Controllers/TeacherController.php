<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Transformers\Json;
use App\User;

class TeacherController extends Controller
{
    public function list()
    {

        $teachers = User::where('rol_code', 'TE')
                      ->where('online', true)
                      ->with(['rol','timeZone','country','timeSchedule','teacherStudents'])
                      ->get()
                      ->map(function ($item) {
                        return [
                          'id' => $item->id,
                          'name' => $item->name, 
                          'email' => $item->email,
                          'avatar' => $item->avatar,
                          'rol' => $item->rol->key,
                          'online' => $item->online,
                          'country' => $item->country->name, 
                          'timeZone' => $item->timeZone->name,                          
                          'ranking'      => round($item->teacherStudents->avg('pivot.ranking')),
                          'timeSchedule' => $item->timeSchedule
                                            ->groupBy('week')
                                              ->map(function ($day) {
                                              return $day->pluck('hour');         
                                            })
                        ];
                      })->toJson();  
        return response()->json(Json::response(compact('teachers')), 200);
    }

    public function show($id)
    {
      $teacher = User::whereId($id)
                  ->where('rol_code','TE')
                  ->with(['rol','timeZone','country','timeSchedule','teacherStudents'])
                  ->get()
                  ->map(
                    function ($item) {
                      return [
                        'id'           => $item->id,
                        'name'         => $item->name,
                        'avatar'       => $item->avatar,
                        'description'  => $item->description,
                        'country'      => $item->country->name,
                        'timeZone'     => $item->timeZone->name,
                        'ranking'      => $item->teacherStudents->pluck('ranking')->avg(),
                        'timeSchedule' => $item->timeSchedule
                                            ->groupBy('week')
                                            ->map(function ($day) {
                                            return $day->pluck('hour');
                        })
                      ];
                    })
                    ->first();
      return response()->json(
        Json::response(compact('teacher')), 
        200
      );
    }
}
