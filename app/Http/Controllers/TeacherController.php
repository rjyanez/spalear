<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Transformers\Json;
use App\User;

class TeacherController extends Controller
{
    public function list(Request $request)
    {
        $auth = $request->user()->id;

        $teachers = User::where('rol_code', 'TE')
                      ->where('online', true)
                      ->with(['rol','timeZone','country','timeSchedule','teacherStudents'])
                      ->get()
                      ->map(function ($item) use ($auth) {
                        return [
                          'id'          => $item->id,
                          'name'        => $item->name, 
                          'email'       => $item->email,
                          'avatar'      => $item->avatar,
                          'rol'         => $item->rol->key,
                          'online'      => $item->online,
                          'country'     => $item->country->name, 
                          'timeZone'    => $item->timeZone->name,
                          'favorite'    => $item->teacherStudents
                                                    ->contains(function ($value, $key) use ($auth) {
                                                      return ($value->id == $auth && $value->pivot->favorite);
                                                    }),                        
                          'ranking'     => round($item->teacherStudents->avg('pivot.ranking')),
                          'timeSchedule'=> $item->timeSchedule->groupBy('week')
                                              ->map(function ($day) {
                                              return $day->pluck('hour');         
                                            })
                        ];
                      })->toJson();  
        return response()->json(Json::response(compact('teachers')), 200);
    }

    public function listFavorites(Request $request)
    {
        $auth = $request->user()->id;

        $teachers = User::where('rol_code', 'TE')
                      ->with(['rol','timeZone','country','timeSchedule','teacherStudents'])
                      ->whereHas('teacherStudents',function ($query)  use ($auth)  {
                                $query->where('pivot.student_id', $auth)
                                      ->where('pivot.favorite', true);
                      })
                      ->get()
                      ->map(function ($item) use ($auth) {
                        return [
                          'id'          => $item->id,
                          'name'        => $item->name, 
                          'email'       => $item->email,
                          'avatar'      => $item->avatar,
                          'rol'         => $item->rol->key,
                          'online'      => $item->online,
                          'country'     => $item->country->name, 
                          'timeZone'    => $item->timeZone->name,
                          'favorite'    => $item->teacherStudents
                                                    ->contains(function ($value, $key) use ($auth) {
                                                      return ($value->id == $auth && $value->pivot->favorite);
                                                    }),                        
                          'ranking'     => round($item->teacherStudents->avg('pivot.ranking')),
                          'timeSchedule'=> $item->timeSchedule->groupBy('week')
                                              ->map(function ($day) {
                                              return $day->pluck('hour');         
                                            })
                        ];
                      })->toJson();  
        return response()->json(Json::response(compact('teachers')), 200);
    }

    public function show($id)
    {
        $auth = $request->user()->id;

        $teachers = User::whereId($id)
                      ->where('rol_code', 'TE')
                      ->with(['rol','timeZone','country','timeSchedule','teacherStudents'])
                      ->get()
                      ->map(function ($item) use ($auth) {
                        return [
                          'id'          => $item->id,
                          'name'        => $item->name, 
                          'email'       => $item->email,
                          'avatar'      => $item->avatar,
                          'description' => $item->description,
                          'rol'         => $item->rol->key,
                          'online'      => $item->online,
                          'country'     => $item->country->name, 
                          'timeZone'    => $item->timeZone->name,
                          'favorite'    => $item->teacherStudents
                                                    ->contains(function ($value, $key) use ($auth) {
                                                      return ($value->id == $auth && $value->pivot->favorite);
                                                    }),                        
                          'ranking'     => round($item->teacherStudents->avg('pivot.ranking')),
                          'timeSchedule'=> $item->timeSchedule->groupBy('week')
                                              ->map(function ($day) {
                                              return $day->pluck('hour');         
                                            })
                        ];
                      })
                      ->first()
                      ->toJson();  

      return response()->json(
        Json::response(compact('teacher')), 
        200
      );
    }


    public function favorite(Request $request){
      $user     = json_decode($request->input('user'));
      $teacher  = json_decode($request->input('teacher'));

      $teacher = User::whereId($teacher->id)
                    ->whereHas('teacherStudents', function ($query) use ($user){
                        $query->where('id', $user->id);
                    })->get();



    }
}
