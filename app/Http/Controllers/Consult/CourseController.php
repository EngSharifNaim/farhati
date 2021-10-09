<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\ConsultCourse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = ConsultCourse::where('consult_id',$request->consult_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'educations list',
            'data' => $courses
        ]);
    }

    public function store(Request $request)
    {
        $course = ConsultCourse::create($request->toArray());

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education added successfully',
            'data' => $course
        ]);

    }
    public function update(Request $request)
    {
        $course = ConsultCourse::where('id',$request->course_id)->first();

        $course->update($request->toArray());
        $course->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education updated successfully',
            'data' => $course
        ]);

    }
    public function destroy(Request $request)
    {

        ConsultCourse::where('id',$request->course_id)->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education deleted successfully',
        ]);

    }
    public function show(Request $request)
    {
        $course = ConsultCourse::where('id','course_id')->first();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education details',
            'data' => $course
        ]);

    }

}
