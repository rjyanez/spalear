<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Transformers\Json;
use App\User;

class TeacherController extends Controller
{
	public function list(Request $request)
	{
		$auth = $request->user()->id;

		$teachers = User::where('rol_code', 'TE')
			->where('online', true)
			->with(['rol', 'timeZone', 'country', 'timeSchedule', 'teacherStudents'])
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
					'timeSchedule' => $item->timeSchedule->groupBy('week')
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
			->with(['rol', 'timeZone', 'country', 'timeSchedule', 'teacherStudents'])
			->whereHas('teacherStudents', function ($query)  use ($auth) {
				$query->where('student_id', $auth)
					->where('favorite', true);
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
					'timeSchedule' => $item->timeSchedule->groupBy('week')
						->map(function ($day) {
							return $day->pluck('hour');
						})
				];
			})->toJson();
		return response()->json(Json::response(compact('teachers')), 200);
	}

	public function show(Request $request, $id)
	{
		$auth = $request->user()->id;

		$teacher = User::whereId($id)
			->where('rol_code', 'TE')
			->with(['rol', 'timeZone', 'country', 'timeSchedule', 'teacherStudents','teacherLessons'])
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
					'ranking'	  => round($item->teacherStudents->avg('pivot.ranking')),
					'timeSchedule'=> $item->timeSchedule->groupBy('week')
										->map(function ($day) {
											return $day->pluck('hour');
										}),
				    'bookedDates'=> $item->teacherLessons->pluck('date')
				];
			})
			->first();
		return response()->json(
			Json::response(compact('teacher')),
			200
		);
	}

	public function favorite(Request $request)
	{
		$id       = $request->input('teacher' );
		$auth     = $request->input('user'    );
		$favorite = $request->input('favorite');
		
		$teacher = User::whereId($id)
			->where('rol_code', 'TE')
			->with(['teacherStudents'])
			->first();			
			
		try {
			$exist = $teacher->teacherStudents->contains(function ($value, $key) use ($auth) {
				return ($value->id == $auth);
			});	
			if(empty($exist)){				
				$teacher->teacherStudents()->attach($auth, ['favorite' => $favorite]);
			} else {
				$teacher->teacherStudents()->updateExistingPivot($auth, ['favorite' => $favorite]);		
			}

			return response()->json(Json::response(
				null,
				($favorite == 'true' ) ? "Successfully added to favorites!" : "Successfully removed to favorites!"
			), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function ranking(Request $request)
	{
		$id       = $request->input('teacher' );
		$auth     = $request->input('user'    );
		$ranking  = $request->input('ranking');
		
		$teacher = User::whereId($id)
			->where('rol_code', 'TE')
			->with(['teacherStudents'])
			->first();			
			
		try {
			$exist = $teacher->teacherStudents->contains(function ($value, $key) use ($auth) {
				return ($value->id == $auth);
			});	
			if(empty($exist)){				
				$teacher->teacherStudents()->attach($auth, ['ranking' => $ranking]);
			} else {
				$teacher->teacherStudents()->updateExistingPivot($auth, ['ranking' => $ranking]);		
			}

			return response()->json(Json::response(
				null,
				"Teacher successfully ranked!"
			), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function message(Request $request)
	{
		$student = User::whereId($request->input('user' ))->first();
		$teacher = User::whereId($request->input('teacher'))->first();

		Notification::route('mail', env('MAIL_USERNAME'))
      ->notify(new NewMessage($student, $teacher, $request->input('message')));		

		return response()->json(Json::response(null,"Message sent successfully"), 200);
	}
}
