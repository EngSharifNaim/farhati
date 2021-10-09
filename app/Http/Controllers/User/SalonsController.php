<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\Review;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\SalonTime;
use App\Models\Service;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalonsController extends Controller
{
    public function get_salons(Request $request)
    {
        $salons = Salon::with('services')
            ->with('services.service')
            ->paginate(10);
        return response()->json([
            'code' => 200,
            'message' => 'Salons',
            'data' => $salons
        ]);


    }
    public function get_near_salons(Request $request)
    {
        $salons = Salon::paginate(10);
        return response()->json([
            'code' => 200,
            'message' => 'Salons',
            'data' => $salons
        ]);


    }
    public function get_services(Request $request)
    {
        $services = Service::all();
        return response()->json([
            'code' => 200,
            'message' => 'Services',
            'data' => $services
        ]);


    }

    public function get_salon_by_id(Request $request)
    {
        $salon = Salon::where('id',$request->salon_id)
            ->with('times')
            ->with('services')
            ->with('services.service')
            ->with('services.images')
            ->first();

        if(WishList::where('salon_id',$salon->id)->where('user_id',Auth::id()))
        {
            $salon->isFavorit = true;
        }
        else
        {
            $salon->isFavorit = true;
        }

        $salon->open = true;

        foreach ($salon->products as $product)
        {
            $product->final_price = $product->price - ($product->price * $product->discount / 100);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Salon page',
            'data' => $salon
        ]);

    }
    public function get_salons_by_service(Request $request)
    {
        $list = json_decode($request->service_id);
        $services = SalonService::whereIn('service_id',$list)
            ->select('salon_id')
            ->get();

        $salons = Salon::whereIn('id',$services)
            ->paginate(10);

        return response()->json([
            'code' => 200,
            'message' => 'Salon page',
            'data' => $salons
        ]);

    }
    public function get_salons_by_keyword(Request $request)
    {
        $salons = Salon::where('name','like','%' . $request->keyword .'%')
            ->paginate(10);


        return response()->json([
            'code' => 200,
            'message' => 'Salon page',
            'data' => $salons
        ]);

    }
    public function get_products_by_service(Request $request)
    {
        $products = Product::where('salon_id',$request->salon_id)
            ->where('service_id',$request->service_id)
            ->paginate(10);

        return response()->json([
            'code' => 200,
            'message' => 'Salon page',
            'data' => $products
        ]);

    }

    public function get_salon_gallery(Request $request)
    {
        $images = Gallery::where('salon_id',$request->salon_id)
            ->get();
        return response()->json([
            'code' => 200,
            'message' => 'Gallery',
            'data' => $images
        ]);

    }
    public function get_salon_reviews(Request $request)
    {
        $reviews = Review::where('salon_id',$request->salon_id)
            ->with('user')
            ->get();
        return response()->json([
            'code' => 200,
            'message' => 'Gallery',
            'data' => $reviews
        ]);

    }

    public function get_free_time(Request $request)
    {
        $data['booked'] = Booking::where('salon_id',$request->salon_id)
            ->where('date',$request->booking_date)
            ->select('time')
            ->get();

        $data['times'] = SalonTime::where('salon_id',$request->salon_id)
            ->where('day',$request->day)
            ->select('time_from','time_to')
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'booked time',
            'data' => $data
        ]);


    }
}
