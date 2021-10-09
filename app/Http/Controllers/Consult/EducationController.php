<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\ConsultEducation;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $educations = ConsultEducation::where('consult_id',$request->consult_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'educations list',
            'data' => $educations
        ]);
    }

    public function store(Request $request)
    {
        $education = ConsultEducation::create($request->toArray());

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education added successfully',
            'data' => $education
        ]);

    }
    public function update(Request $request)
    {
        $education = ConsultEducation::where('id',$request->education_id)->first();

        $education->update($request->toArray());
        $education->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education updated successfully',
            'data' => $education
        ]);

    }
    public function destroy(Request $request)
    {

       ConsultEducation::where('id',$request->education_id)->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education deleted successfully',
        ]);

    }
    public function show(Request $request)
    {
        $education = ConsultEducation::where('id','education_id')->first();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education details',
            'data' => $education
        ]);

    }
}
