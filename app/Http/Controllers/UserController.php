<?php

namespace App\Http\Controllers;

use App\User;
use App\Country;
use App\TimeSchedule;
use App\TimeZone;
use App\Meeting;
use App\CodeMeta;
use App\Notifications\NewUser;
use App\Transformers\Json;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

	public function list()
	{
		$users = User::with(['roles', 'timeZone', 'country'])
			->get()
			->map(function ($item) {
				return [
					'id' => $item->id,
					'name' => $item->name,
					'email' => $item->email,
					'roles' => $item->roles->pluck('value'),
					'country' => $item->country->name,
					'timeZone' => $item->timeZone->name
				];
			})->toJson();
		$roles = CodeMeta::where('type', 'rol')->pluck('value', 'key');

		return response()->json(Json::response(compact('users', 'roles')), 200);
	}

	public function store(Request $request)
	{
		$password = str_random(8);

		$user = new User([
			'name'         => $request->input('name'),
			'email'        => $request->input('email'),
			'password'     => Hash::make($password),
			'country_code' => $request->input('country_code'),
			'time_zone_id' => $request->input('time_zone_id'),
			'notify'			 => ($request->input('notify')) ? $request->input('notify') : 0,
			'description'  => $request->input('description'),
		]);
		if ($user->save()) {
				$roles = explode(',', $request->input('rolcodes'));
				if ($request->hasFile('avatar')) $user->avatar = $this->saveAvatar($request->file('avatar'), $user->id);
				if (in_array('TE', $roles)) $this->saveTimeSchedule(json_decode($request->input('time_schedule')),  $user->id);
				if (in_array('ST', $roles)) {
					if (!$user->level) $user->level = 'BAS';
					if (!$user->sort) $user->sort = 'NEU';
				}
				$user->roles()->sync($roles);

				$user->save();
				$user->notify(new NewUser($password));
				$user = $this->getUserById($user->id);

				return response()->json(Json::response(compact('user'), 'Successfully created user!'), 200);
			} else {
			return response()->json(null, 401);
		}
	}

	public function show($id)
	{
		$user = $this->getUserById($id);

		$roles = CodeMeta::where('type', 'rol')->pluck('value', 'key');
		$countries = Country::with(['timeZones'])->pluck('name', 'code');
		$timeZones = TimeZone::select('id', DB::raw("name ||' '||gmt_offset as name"), 'country_code')
			->get()
			->groupBy('country_code')
			->map(function ($item, $key) {
				return $item->pluck('name', 'id');
			});

		return response()->json(
			Json::response(compact('user', 'roles', 'countries', 'timeZones')),
			200
		);
	}

	public function roles($id)
	{
		$roles = User::whereId($id)->with(['roles'])->first()->roles->pluck('key');

		return response()->json(
			Json::response(compact('roles')),
			200
		);
	}

	public function update(Request $request, $id)
	{
		$user = User::find($id);
		$user->name = $request->input('name');
		$user->email = $request->input('email');
		$user->country_code = $request->input('country_code');
		$user->time_zone_id = $request->input('time_zone_id');
		$user->description = $request->input('description');
		$user->notify			 = ($request->input('notify')) ? $request->input('notify') : 0;

		if ($user->save()) {
			$roles = explode(',', $request->input('rolcodes'));

			if ($request->hasFile('avatar')) $user->avatar = $this->saveAvatar($request->file('avatar'), $user->id);
			if (in_array('TE', $roles)) $this->saveTimeSchedule(json_decode($request->input('time_schedule')),  $user->id);
			if (in_array('ST', $roles)) {
				if (!$user->level) $user->level = 'BAS';
				if (!$user->sort) $user->sort = 'NEU';
			}
			$user->roles()->sync($roles);

			$user->save();
			$user = $this->getUserById($id);

			return response()->json(Json::response(compact('user'), 'Successfully updated user!'), 200);
		} else {
			return response()->json(null, 401);
		}
	}

	public function getUserById($id)
	{
		return User::whereId($id)
			->with([
				'roles',
				'timeZone',
				'country',
				'timeSchedule'
			])
			->get()
			->map(
				function ($item) {
					return [
						'id' => $item->id,
						'name' => $item->name,
						'email' => $item->email,
						'avatar' => $item->avatar,
						'description' => $item->description,
						'notify'	=> $item->notify,
						'roles' => $item->roles->pluck('key'),
						'country_code' => $item->country_code,
						'country' => $item->country->name,
						'time_zone_id' => $item->time_zone_id,
						'timeZone' => $item->timeZone->name,
						'timeSchedule' => $item->timeSchedule
							->groupBy('week')
							->map(function ($day) {
								return $day->pluck('hour');
							})
					];
				}
			)
			->first();
	}

	public function destroy($id)
	{
		$user = User::find($id);
		if ($user->delete()) {
			return response()->json(Json::response(null, 'Successfully destroied user!'), 200);
		} else {
			return response()->json(null, 401);
		}
	}

	public function saveAvatar($file, $id)
	{
		$extension = $file->getClientOriginalExtension();
		$filename = $id . '.' . $extension;
		if (Storage::disk('uploads')->exists('avatar/' . $filename)) Storage::disk('uploads')->delete('avatar/' . $filename);
		Storage::disk('uploads')->put('avatar/' . $filename, file_get_contents($file));
		return $filename;
	}

	public function saveTimeSchedule($times, $id)
	{
		if (!empty($times)) :
			TimeSchedule::where('user_id', $id)->delete();
			$data = [];
			foreach ($times as $time) :
				array_push($data, [
					'user_id' => $id,
					'week'     => $time->week,
					'hour'    => $time->hour . ':' . $time->min
				]);
			endforeach;
			TimeSchedule::insert($data);
		endif;
	}

	public function unique($email)
	{
		$user = User::where('email', $email)->first();
		return response()->json(Json::response(empty($user), null), 200);
	}
}
