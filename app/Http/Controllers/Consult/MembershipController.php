<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\ConsultMembership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $memberships =ConsultMembership::where('consult_id',$request->consult_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'memberships list',
            'data' => $memberships
        ]);
    }

    public function store(Request $request)
    {
        $membership = ConsultMembership::create($request->toArray());

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'experience added successfully',
            'data' => $membership
        ]);

    }
    public function update(Request $request)
    {
        $membership = ConsultMembership::where('id',$request->membership_id)->first();

        $membership->update($request->toArray());
        $membership->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'membership updated successfully',
            'data' => $membership
        ]);

    }
    public function destroy(Request $request)
    {

        ConsultMembership::where('id',$request->membership_id)->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'membership deleted successfully',
        ]);

    }

}
