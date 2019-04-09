<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use App\Transformers\Json;
use App\Meeting;
use App\User;
use App\Notifications\NewMeeting;

class MeetingController extends Controller
{
	public function store(Request $request)
	{
		try {
			$student = $request->input('student');
			$teacher = $request->input('teacher');
			$lesson  = $request->input('lesson');
			$type    = $request->input('type');

			foreach (json_decode($request->input('data')) as $key => $value) :
				$data = [
					'student' => $student,
					'teacher' => $teacher,
					'lesson'  => $lesson,
					'type'    => $type,
					'date'    => $value
				];

				$save = $this->create($data);
				$class = Meeting::whereId($save->id)
						->with(['status', 'type', 'student', 'teacher'])
						->first();	
				
				$class->teacher->notify(new NewMeeting($class));	


			endforeach;
			return response()->json(Json::response(null, 'Class Schedule Successfully!'), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}

	public function create($data)
	{
		$class = new Meeting([
			'student_id' => $data['student'],
			'teacher_id' => $data['teacher'],
			'type_code'  => $data['type'],
			'lesson_id'  => $data['lesson'],
			'date'       => $data['date']
		]);
		return ($class->save()) ? $class : false;
	}

	public function destroy($id)
	{
		try {
			$class = Meeting::find($id);
			$class->delete();
			return response()->json(Json::response(null, 'Successfully destroied class!'), 200);
		} catch (\Throwable $th) {
			return response()->json(null, 401);
		}
	}
}
