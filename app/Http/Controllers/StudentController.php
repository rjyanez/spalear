<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformers\Json;
use App\User;

class StudentController extends Controller
{
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
					'level' 	  => $item->level,
					'sort' 		  => $item->sort,
					'roles'       => $item->roles,
					'country'     => $item->country->name,
					'timeZone'    => $item->timeZone->name,
					'progress'	  => $this->progress($item->studentMeetings),
					'meetings'	  => $this->meetingsShortList($item->studentMeetings)					
				];
			})
			->first()
			;

		return response()->json(
			Json::response(compact('student')),
			200
		);
	}

	public function progress($meetings)
	{
		$meetings = $meetings->filter(function($item, $key){
			return $item->status_code === 'FIN';

		});

		$progress = [
			'teachers'=> $meetings->pluck('teacher_id')->unique()->count(),
			'conversational'=> $meetings->filter(function ($item, $key) {
				return $item->lesson->type->key === 'CN';
			})->count(),
			'grammatical'=> $meetings->filter(function ($item, $key) {
				return $item->lesson->type->key === 'GR';
			})->count(),
		];

		return $progress;
	}

	public function meetingsShortList($meetings)
	{
		return $meetings->filter(function($item, $key){
						return in_array($item->status_code, ['ACT','PEN']);
					})
					->take(5)
					->map(function ($value, $key) {
						return [
							'id'      => $value->id,
							'url'	=> $value->url,
							'date'    => $value->date,
							'student	' => $value->student,
							'teacher' => $value->teacher,
							'type'    => $value->type->value,
							'lesson'  => ($value->lesson) ? $value->lesson->name : false,
							'level'   => ($value->lesson) ? $value->lesson->level->value : false
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
			return response()->json(Json::response(null,'Successfully updated user!'), 200);
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
			return response()->json(Json::response(null,'Successfully updated user!'), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

}
