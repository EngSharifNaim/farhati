<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Consult;
use App\Models\ConsultReview;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Cast\Double;

class ConsultController extends Controller
{
    public function distance($point1_lat, $point1_long, $point2_lat, $point2_long, $decimals = 2) {
        $lon1= $point1_long;
        $lat1 = $point1_lat;
        $lon2 =$point2_long;
        $lat2 =$point2_lat;
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return round($miles * 1.609344,$decimals);
    }

    public function activeConsult()
    {
        $consults = Consult::where('active',1)->get();

        return response()->json([
            'code' => 200,
            'message' => 'Active consults List',
            'data' => $consults
        ]);

    }
    public function consultByLocation()
    {
        $user_long = Auth::user()->location_long;
        $user_lat = Auth::user()->location_lat;

        $consults = Consult::where('active',1)->get();

        foreach ($consults as $consult)
        {
            $consult_long = (float)$consult->location_long;
            $consult_lat = (float)$consult->location_lat;
            $consult->distance = $this->distance($user_lat,$user_long,$consult_lat,$consult_long,2);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Active consults List',
            'data' => $consults
        ]);

    }
    public function allConsult()
    {
        $consults = Consult::all();

        return response()->json([
            'code' => 200,
            'message' => 'All consults List',
            'data' => $consults
        ]);

    }
    public function searchConsult(Request $request)
    {
        $consults = Consult::where('name','like','%' . $request->keyword . '%')->get();

        return response()->json([
            'code' => 200,
            'message' => 'search result',
            'data' => $consults
        ]);

    }

    public function showConsultPage(Request $request)
    {
        $consult = Consult::where('id',$request->consult_id)
            ->with('reviews')
            ->with('reviews.user')
            ->with('services')
            ->with('services.type')
            ->first();

        $isFavorite = WishList::where('user_id',Auth::id())
            ->where('consult_id',$consult->id)
            ->first();
        if($isFavorite)
        {
            $consult->isFavorite = true;
        }
        else
        {
            $consult->isFavorite = false;

        }

        $rates['5_stars'] = ConsultReview::where('consult_id',$request->consult_id)
            ->where('rate',5)
            ->count();
        $rates['4_stars'] = ConsultReview::where('consult_id',$request->consult_id)
            ->where('rate',4)
            ->count();
        $rates['3_stars'] = ConsultReview::where('consult_id',$request->consult_id)
            ->where('rate',3)
            ->count();
        $rates['2_stars'] = ConsultReview::where('consult_id',$request->consult_id)
            ->where('rate',2)
            ->count();
        $rates['1_stars'] = ConsultReview::where('consult_id',$request->consult_id)
            ->where('rate',1)
            ->count();
        $rates['total'] = ConsultReview::where('consult_id',$request->consult_id)
            ->count();
        $rates['average'] = ConsultReview::where('consult_id',$request->consult_id)
            ->average('rate');

        $consult->rates = $rates;
        return response()->json([
            'code' => 200,
            'message' => 'Consult page',
            'data' => $consult
        ]);

    }
}
