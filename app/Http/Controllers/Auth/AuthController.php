<?php
namespace App\Http\Controllers\Auth;

use App\Country;
use App\TimeZone;
use App\User;
use App\Transformers\Json;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Validator;



class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(Json::response(null,$validator->messages()));
        }
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    Json::response(null,'We can`t find an account with this credentials.')
                    , 401);
            }
        } catch (JWTException $e) {
            return response()->json(Json::response(null,'Failed to login, please try again.'), 500);
        }

        if($token){
            $user =  Auth::user();
            $user->online = true;
            $user->save();
            
            return response()->json(Json::response([
                    'access_token' => $token,
                    'user' =>$this->requestUser()
                ],'Login success, welcome back!'));
        }
    }
    public function logout(Request $request) 
    {
        try {
            $user = Auth::user();
            $user->online = false;
            $user->save();
            JWTAuth::parseToken()->invalidate();
            return response()->json(Json::response(null,"User successfully logged out."));
        } catch (JWTException $e) {
            return response()->json(Json::response(null,'Failed to logout, please try again.'), 500);
        }
    }


    public function refreshToken(){
        $token = JWTAuth::getToken();
        if(!$token){
            throw new BadRequestHtttpException('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            throw new AccessDeniedHttpException('The token is invalid');
        }
        return response()->json(Json::response([                
                'access_token' => $token,
                'user' => $this->requestUser()
            ]));
    }

    public function user(Request $request)
    {
        return response()->json($this->requestUser());
    }

    public function create()
    {  
      $countries = Country::with(['timeZones'])->pluck('name', 'code');
      $timeZones = TimeZone::select('id', DB::raw("name ||' '||gmt_offset as name"), 'country_code')
            ->get()
            ->groupBy('country_code')
            ->map(function ($item, $key) {
              return $item->pluck('name', 'id');
            });
      return response()->json(Json::response(compact('countries', 'timeZones')), 200);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        $user = new User([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make ($request->password),
            'country_code' => $request->country_code,
            'time_zone_id' => $request->time_zone_id,
            'level'        => 'BAS',
            'sort'         => 'NEU'
        ]);
        $user->save();
        $user->roles()->attach('ST');
        return response()->json([
            'message' => 'Successfully created user!'], 201);
    }

    public function requestUser(){
        return User::whereId(Auth::user()->id)->with('roles')->first();
    }
}