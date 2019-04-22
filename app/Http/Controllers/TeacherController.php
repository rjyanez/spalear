<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Notifications\NewMessage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use App\Transformers\Json;
use App\User;
use Illuminate\Support\Carbon;


class TeacherController extends Controller
{
	public function list(Request $request)
	{
		$auth = $request->user()->id;

		$teachers = User::whereHas('roles', function ($q) {
			$q->where('key', 'TE');
		})
			->where('online', true)
			->with(['roles', 'timeZone', 'country', 'timeSchedule', 'teacherStudents'])
			->get()
			->map(function ($item) use ($auth) {
				return [
					'id'          => $item->id,
					'name'        => $item->name,
					'email'       => $item->email,
					'avatar'      => $item->avatar,
					'roles'       => $item->roles->pluck('value'),
					'online'      => $item->online,
					'country'     => $item->country->name,
					'timeZone'    => $item->timeZone->name,
					'teacherStudents' => $item->teacherStudents,
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

		$teachers = User::whereHas('roles', function ($q) {
			$q->where('key', 'TE');
		})
			->with(['roles', 'timeZone', 'country', 'timeSchedule', 'teacherStudents'])
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
					'roles'       => $item->roles,
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
		$now = Carbon::now();

		$teacher = User::whereId($id)
			->whereHas('roles', function ($q) {
				$q->where('key', 'TE');
			})
			->with(['roles', 'timeZone', 'country', 'timeSchedule', 'teacherStudents', 'teacherMeetings'])
			->get()
			->map(function ($item) use ($auth, $now) {
				return [
					'id'          => $item->id,
					'name'        => $item->name,
					'email'       => $item->email,
					'avatar'      => $item->avatar,
					'description' => $item->description,
					'roles'       => $item->roles,
					'online'      => $item->online,
					'country'     => $item->country->name,
					'timeZone'    => $item->timeZone->name,
					'favorite'    => $item->teacherStudents
						->contains(function ($value, $key) use ($auth) {
							return ($value->id == $auth && $value->pivot->favorite);
						}),
					'ranking'	  => round($item->teacherStudents->avg('pivot.ranking')),
					'timeSchedule' => $item->timeSchedule->groupBy('week')
						->map(function ($day) {
							return $day->pluck('hour');
						}),
					'bookedDates' => $item->teacherMeetings->filter(function($item) use ($now){
						return $item->date >= $now;
					})->pluck('date')
				];
			})
			->first();
		return response()->json(
			Json::response(compact('teacher')),
			200
		);
	}

	public function dashboard(Request $request, $id)
	{
		$now = Carbon::now();
		$teacher = User::whereId($id)
			->with(['roles', 'timeSchedule', 'teacherMeetings'])
			->whereHas('roles', function ($q) {
				$q->where('key', 'TE');
			})
			->get()
			->map(function ($item) use ($now) {
				return [
					'id'          => $item->id,
					'roles'       => $item->roles,
					'bookedDates' => $item->teacherMeetings->filter(function($item, $now){
						return $item->date >= $now;
					})->pluck('date'),
					'timeSchedule' => $item->timeSchedule->groupBy('week')
						->map(function ($day) {
							return $day->pluck('hour');
						}),
					'meetings'	=> $item->teacherMeetings->take(5)
						->map(function ($value, $key) {
							return [
								'id'      => $value->id,
								'url'	=> $value->url,
								'date'    => $value->date,
								'student' => $value->student,
								'teacher' => $value->teacher,
								'type'    => $value->type->value,
								'lesson'  => ($value->lesson) ? $value->lesson->name : false,
								'level'   => ($value->lesson) ? $value->lesson->level->value : false
							];
						})
						->toArray(),
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
		$id       = $request->input('teacher');
		$auth     = $request->input('user');
		$favorite = $request->input('favorite');

		$teacher = User::whereId($id)
			->whereHas('roles', function ($q) {
				$q->where('key', 'TE');
			})
			->with(['teacherStudents'])
			->first();

		try {
			$exist = $teacher->teacherStudents->contains(function ($value, $key) use ($auth) {
				return ($value->id == $auth);
			});
			if (empty($exist)) {
				$teacher->teacherStudents()->attach($auth, ['favorite' => $favorite]);
			} else {
				$teacher->teacherStudents()->updateExistingPivot($auth, ['favorite' => $favorite]);
			}

			return response()->json(Json::response(
				null,
				($favorite == 'true') ? "Successfully added to favorites!" : "Successfully removed to favorites!"
			), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function ranking(Request $request)
	{
		$id       = $request->input('teacher');
		$auth     = $request->input('user');
		$ranking  = $request->input('ranking');

		$teacher = User::whereId($id)
			->whereHas('roles', function ($q) {
				$q->where('key', 'TE');
			})
			->with(['teacherStudents'])
			->first();

		try {
			$exist = $teacher->teacherStudents->contains(function ($value, $key) use ($auth) {
				return ($value->id == $auth);
			});
			if (empty($exist)) {
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
		$student = User::whereId($request->input('from'))->first();
		$teacher = User::whereId($request->input('to'))->first();

		Notification::route('mail', env('MAIL_USERNAME'))
			->notify(new NewMessage($student, $teacher, $request->input('message')));

		return response()->json(Json::response(null, "Message sent successfully"), 200);
	}
}
