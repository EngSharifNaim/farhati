<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\ConsultExperience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $experiences = ConsultExperience::where('consult_id',$request->consult_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'educations list',
            'data' => $experiences
        ]);
    }

    public function store(Request $request)
    {
        $experience = ConsultExperience::create($request->toArray());

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'experience added successfully',
            'data' => $experience
        ]);

    }
    public function update(Request $request)
    {
        $experience = ConsultExperience::where('id',$request->experience_id)->first();

        $experience->update($request->toArray());
        $experience->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education updated successfully',
            'data' => $experience
        ]);

    }
    public function destroy(Request $request)
    {

        ConsultExperience::where('id',$request->experience_id)->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education deleted successfully',
        ]);

    }
    public function show(Request $request)
    {
        $experience = ConsultExperience::where('id','experience_id')->first();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'education details',
            'data' => $experience
        ]);

    }

}
