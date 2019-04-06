<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use App\Transformers\Json;
use App\Classes;
use App\User;
use App\Notifications\NewClass;

class ClassesController extends Controller
{
	public function store(Request $request)
	{
		// try {
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
				$class = Classes::whereId($save->id)
						->with(['status', 'type', 'student', 'teacher'])
						->first();	
				
				$class->teacher->notify(new NewClass($class));	


			endforeach;
			return response()->json(Json::response(null, 'Class Schedule Successfully!'), 200);
		// } catch (\Throwable $th) {
		// 	return response()->json(null, 401);
		// }
	}

	public function create($data)
	{
		$class = new Classes([
			'student_id' => $data['student'],
			'teacher_id' => $data['teacher'],
			'type_code'  => $data['type'],
			'lesson_id'  => $data['lesson'],
			'date'       => $data['date']
		]);
		return ($class->save()) ? $class : false;
	}
}
