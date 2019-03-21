<?php

namespace App\Http\Controllers;

use App\User;
use App\Country;
use App\TimeSchedule;
use App\TimeZone;
use App\Rol;
use App\Transformers\Json;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

  public function list(){
    $users = User::with(['rol','timeZone','country'])
                  ->get()
                  ->map(function ($item) {
                    return [
                      'id' => $item->id,
                      'name' => $item->name, 
                      'email' => $item->email,
                      'rol' => $item->rol->name,
                      'country' => $item->country->name, 
                      'timeZone' => $item->timeZone->name
                    ];
                  })->toJson();  
    return response()->json(Json::response(compact('users')), 200);
  }

  public function store(Request $request)
  {
    $user = new User([
      'name'         => $request->input('name'),
      'email'        => $request->input('email'),
      'password'     => Hash::make ('123456'),
      'country_code' => $request->input('country_code'),
      'time_zone_id' => $request->input('time_zone_id'),
      'description'  => $request->input('description'),
      'rol_code'     => $request->input('rol_code'),
    ]);
    if($user->save()){
      if($request->hasFile('avatar')){
        $user->avatar = $this->saveAvatar($request->file('avatar'), $user->id);
        $user->save();
      }
      if($user->rol_code == 'TE') $this->saveTimeSchedule(json_decode($request->input('time_schedule')),  $user->id); 
      return response()->json(Json::response(compact('user'), 'Successfully created user!'), 200);         
    } else {
      return response()->json(null, 401);  
    }
  }

  public function show($id)
  {
    $user = User::whereId($id)->first();
    $roles = Rol::where('code', '!=', 'ST')->pluck('name', 'code');
    $countries = Country::with(['timeZones'])->pluck('name', 'code');
    $timeZones = TimeZone::select('id', DB::raw("name ||' '||gmt_offset as name"), 'country_code')
          ->get()
          ->groupBy('country_code')
          ->map(function ($item, $key) {
            return $item->pluck('name', 'id');
          });

    $user->setAttribute('time_schedule', ($user->rol_code == 'TE')?
      TimeSchedule::select('day','hour')
          ->where('user_id', $id)
          ->get()
          ->groupBy('day')
          ->map(function ($item, $key) {
            return $item->pluck('hour');
          }) 
      : []
    );
          
    return response()->json(Json::response(compact('user','countries', 'timeZones','roles')), 200);
  }
  
  public function update(Request $request, $id)
  {
    $user = User::find($id);
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->country_code = $request->input('country_code');
    $user->time_zone_id = $request->input('time_zone_id');
    $user->description = $request->input('description');
    $user->rol_code = ($request->input('rol_code'))? $request->input('rol_code') :  $user->rol_code ;
    if($user->save()){
      if($request->hasFile('avatar')){
        $user->avatar = $this->saveAvatar($request->file('avatar'), $user->id); ;
        $user->save();
      }
      if($user->rol_code == 'TE') $this->saveTimeSchedule(json_decode($request->input('time_schedule')),  $user->id);

      $user->setAttribute('time_schedule', ($user->rol_code == 'TE')?
        TimeSchedule::select('day','hour')
            ->where('user_id', $id)
            ->get()
            ->groupBy('day')
            ->map(function ($item, $key) {
              return $item->pluck('hour');
            }) 
        : []
      );

      return response()->json(Json::response(compact('user'),'Successfully updated user!'), 200);        
    } else {
      return response()->json(null, 401);  
    }
  }
  
  public function destroy($id)
  {
    $user = User::find($id);
    if($user->delete()){
      return response()->json(Json::response(null,'Successfully destroied user!'), 200);         
    } else {
      return response()->json(null, 401);  
    }        
  }

  public function saveAvatar($file,$id){
    $extension = $file->getClientOriginalExtension();
    $filename = $id.'.'.$extension;
    if(Storage::disk('uploads')->exists('avatar/'.$filename)) Storage::disk('uploads')->delete('avatar/'.$filename);
    Storage::disk('uploads')->put('avatar/'.$filename, file_get_contents($file));
    return $filename;
  }

  public function saveTimeSchedule($times,$id){
    if(!empty($times)):
      TimeSchedule::where('user_id', $id)->delete();
      $data= [];
      foreach ($times as $day => $hours):
        if(!empty($hours)){
          foreach ($hours as $hour):
            array_push($data, [
              'user_id' => $id,
              'day'     => $day ,
              'hour'    => $hour
            ]);
          endforeach;
        }
      endforeach;
      TimeSchedule::insert($data);
    endif;    
  }
  

}
