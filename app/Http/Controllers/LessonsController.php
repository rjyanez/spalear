<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformers\Json;
use App\Lesson;

class LessonsController extends Controller
{
    function list($level){

        $lessons = Lesson::where('level_code', $level)->get();

        return response()->json(
			Json::response(compact('lessons')),
			200
		);

    }
}
