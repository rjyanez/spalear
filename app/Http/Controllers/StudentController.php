<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformers\Json;
use App\User;
use Carbon\Carbon;

class StudentController extends Controller
{

	public function list(Request $request)
	{
		$id = $request->user()->id;
		$students = User::whereHas('roles', function ($q) {
									$q->where('key', 'ST');
								})
								->whereHas('studentMeetings', function ($q) use ($id) {
									$q->where('teacher_id', $id);
								})
								->get()
								->map(function ($item) {
									return [
										'id'          => $item->id,
										'name'        => $item->name,
										'email'       => $item->email,
										'avatar'      => $item->avatar,
										'level'       => $item->level,
										'sort'        => $item->sort,
										'country'     => $item->country->name,
										'timeZone'    => $item->timeZone->name,
									];
								});

		return response()->json(Json::response(compact('students')), 200);
	}

	public function show(Request $request, $id)
	{
		$student = User::whereId($id)
			->whereHas('roles', function ($q) {
				$q->where('key', 'ST');
			})
			->with([
				'roles',
				'timeZone',
				'country',
				'receivedMessages' => function ($query) {
					return $query->orderBy('messages.id', 'desc');
				},
				'studentMeetings' => function ($query) {
					return $query->orderBy('date', 'asc');
				}
			])
			->get()
			->map(function ($item) {
				return [
					'id'          => $item->id,
					'name'        => $item->name,
					'email'       => $item->email,
					'avatar'      => $item->avatar,
					'description' => $item->description,
					'level'       => $item->level,
					'sort'        => $item->sort,
					'roles'       => $item->roles,
					'country'     => $item->country->name,
					'timeZone'    => $item->timeZone->name,
					'progress'    => $this->progress($item->studentMeetings),
					'meetings'    => $this->meetingsShortList($item->studentMeetings),
					'messages'		=> $this->receivedMessagesList($item->receivedMessages)
				];
			})
			->first();

		return response()->json(
			Json::response(compact('student')),
			200
		);
	}

	public function showShort(Request $request, $id)
	{
		$student = User::whereId($id)
			->whereHas('roles', function ($q) {
				$q->where('key', 'ST');
			})
			->get()
			->map(function ($item) {
				return [
					'id'          => $item->id,
					'name'        => $item->name,
					'email'       => $item->email,
					'avatar'      => $item->avatar,
					'description' => $item->description,
					'level'       => $item->level,
					'sort'        => $item->sort,
					'roles'       => $item->roles,
					'country'     => $item->country->name,
					'timeZone'    => $item->timeZone->name
				];
			})
			->first();

		return response()->json(
			Json::response(compact('student')),
			200
		);
	}

	public function progress($meetings)
	{
		$meetings = $meetings->filter(function ($item, $key) {
			return $item->status_code === 'FIN';
		});

		$progress = [
			'teachers' => $meetings->pluck('teacher_id')->unique()->count(),
			'conversational' => $meetings->filter(function ($item, $key) {
				return $item->lesson->type->key === 'CN';
			})->count(),
			'grammatical' => $meetings->filter(function ($item, $key) {
				return $item->lesson->type->key === 'GR';
			})->count(),
		];

		return $progress;
	}

	public function meetingsShortList($meetings)
	{
		return $meetings->filter(function ($item, $key) {
			return in_array($item->status_code, ['ACT', 'PEN']);
		})
			->take(5)
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
			->toArray();
	}

	public function receivedMessagesList($messages)
	{
		return $messages->map(function ($value, $key) {
			return [
				'id' => $value->pivot->id,
				'message' => $value->pivot->message,
				'created_at' => $value->pivot->created_at,
				'from' => [
					'id'     => $value->id     ,
					'avatar' => $value->avatar,
					'name'   => $value->name
				]
			];
		})
		->toArray();
	}


	public function sort(Request $request)
	{
		try {
			$student = User::whereId($request->input('student'))
				->whereHas('roles', function ($q) {
					$q->where('key', 'ST');
				})->first();
			$student->sort = $request->input('sort');
			$student->save();
			return response()->json(Json::response(null, 'Successfully updated user!'), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function level(Request $request)
	{
		try {
			$student = User::whereId($request->input('student'))
				->whereHas('roles', function ($q) {
					$q->where('key', 'ST');
				})->first();
			$student->level = $request->input('level');
			$student->save();
			return response()->json(Json::response(null, 'Successfully updated user!'), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function message(Request $request)
	{
		try {
			$student = User::whereId($request->input('to'))->first();
			$student->receivedMessages()->attach(
				$request->input('from'), 
				[
					'message' => $request->input('message'),
					'created_at'=> Carbon::now()
				]);
			return response()->json(Json::response(null, "Message sent successfully"), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}
}
