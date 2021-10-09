<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Review;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\SalonTime;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorReview;
use App\Models\Work;
use Illuminate\Http\Request;
//use Kreait\Firebase\Auth;
use Illuminate\Support\Facades\Auth;
class VendorsController extends Controller
{
    public function get_all_vendors(Request $request)
    {
        $data['vendors'] = Vendor::with('services')
            ->with('services.category')
            ->get();
        foreach ($data['vendors'] as $vendor)
        {
            $vendor->distance = 2.4;
            $vendor->isFavorit = false;
            $vendor->open = true;
            $vendor->work_time = '12:00 - 08:00';
        }
        $data['sub_categories'] = Category::where('parent',$request->category_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'companies list',
            'data' => $data
        ]);

    }
    public function get_vendor_by_category(Request $request)
    {
        $data['vendors'] = Vendor::where('category_id',$request->category_id)
            ->with('services')
            ->with('services.category')
            ->get();
        foreach ($data['vendors'] as $vendor)
        {
            $vendor->distance = 2.4;
            $vendor->isFavorit = false;
            $vendor->open = true;
            $vendor->work_time = '12:00 - 08:00';
        }
        $data['sub_categories'] = Category::where('parent',$request->category_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'companies list',
            'data' => $data
        ]);

    }
    public function get_vendor_by_location(Request $request)
    {
        $data['vendors'] = Vendor::get();
        foreach ($data['vendors'] as $vendor)
        {
            $vendor->distance = 2.4;
            $vendor->isFavorit = false;
            $vendor->open = true;
            $vendor->work_time = '12:00 - 08:00';
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'companies list',
            'data' => $data
        ]);

    }

    public function get_vendor_by_id(Request $request)
    {
        $vendor = Vendor::where('id',$request->vendor_id)
            ->with('team')
            ->with('reviews')
            ->with('reviews.user')
            ->with('services')
            ->with('services.category')
            ->first();
            $vendor->distance = 2.4;
            $vendor->isFavorit = false;
            $vendor->open = true;
            $vendor->work_time = '12:00 - 08:00';

            $reviews[1] = VendorReview::where('vendor_id',$request->vendor_id)
                ->where('rate',1)->count();
            $reviews[2] = VendorReview::where('vendor_id',$request->vendor_id)
                ->where('rate',2)->count();
            $reviews[3] = VendorReview::where('vendor_id',$request->vendor_id)
                ->where('rate',3)->count();
            $reviews[4] = VendorReview::where('vendor_id',$request->vendor_id)
                ->where('rate',4)->count();
            $reviews[5] = VendorReview::where('vendor_id',$request->vendor_id)
                ->where('rate',5)->count();

            $reviewssum = VendorReview::where('vendor_id',$request->vendor_id)
                ->sum('rate');
            $reviewscount = VendorReview::where('vendor_id',$request->vendor_id)
                ->count();

            if($reviewscount > 0)
            {
                $vendor->rate = $reviewssum / $reviewscount;
            }
            else
            {
                $vendor->rate = 0;
            }



            $vendor->reviews_scores = $reviews;
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Vendor details',
            'data' => $vendor
        ]);

    }

    public function get_vendor_works(Request $request)
    {
        $works = Work::where('vendor_id',$request->vendor_id)->get();

        foreach ($works as $work)
        {
            $work->image = json_decode($work->images,true);
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Vendor works',
            'data' => $works
        ]);

    }

    public function get_gallery_by_vendor(Request $request)
    {
        $images = Gallery::where('vendor_id',$request->vendor_id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'images list',
            'data' => $images
        ]);

    }


    public function get_categories(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $categories = Category::where('parent',0)->get();

        foreach ($categories as $category)
        {
            if(VendorCategory::where('vendor_id',$vendor->id)->where('category_id',$category->id)->first())
            {
                $category -> isMine = true;
            }
            else
                {
                    $category -> isMine = false;

                }

        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'categories list',
            'data' => $categories
        ]);

    }
    public function set_categories(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $categories = json_decode($request->categories);

        VendorCategory::where('vendor_id',$vendor->id)->delete();
        for ($i=0;$i<count($categories);$i++)
        {
            $category = new VendorCategory();
            $category->vendor_id = $vendor->id;
            $category->category_id = $categories[$i];
            $category->save();
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'categories updated',
        ]);

    }

    public function get_my_works(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $works = Work::where('vendor_id',$vendor->id)
            ->get();
        foreach ($works as $work)
        {
            $work->image = json_decode($work->images,true);
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'products list',
            'data' => $works
        ]);


    }

    public function add_work(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $data = $request->input();
        $data['vendor_id'] = $vendor->id;

        Work::create($data);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'work added',
        ]);

    }

    public function get_profile(Request $request)
    {
        $user = User::where('id',Auth::id())->with('salon')->first();

        $user->salon->image_url = json_decode($user->salon->image_url,true);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Profile',
            'data' => $user
        ]);
    }
    public function set_profile(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $wordDays = json_decode($request->workDays,true);
        foreach ($wordDays as $day)
        {
            $work = new SalonTime();
            $work -> salon_id = $vendor->id;
            $work -> day = $day['day'];
            $work -> time_from = $day['from'];
            $work -> time_to = $day['to'];
            $work -> save();
        }
        if($request->hasFile('photos')) {
            $attachments = [];
            foreach ($request->file('photos') as $file) {
                $image = $file;
                $name = time() . '_' . mt_rand(2000, 90000000) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/uploads');
                $image->move($destinationPath, $name);

                $attachments[] = url('/public/uploads/') . '/' . $name;

            }
            $vendor -> image_url = $attachments;
            $vendor->save();

        }
        if($request->about)
        {
            $vendor -> about = $request->about;
            $vendor->save();
        }
        if($request->hasFile('logo'))
        {
            $image = $request->file('logo');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $vendor -> logo_url = url('/public/uploads/') . '/' . $name;
            $vendor->save();
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'categories list'
        ]);


    }

    public function add_service(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $service = new SalonService();
        $service -> salon_id = $vendor->id;
        $service -> service_id = $request->service_id;
        $service -> name = $request->name;
        $service -> price = $request->price;
        $service -> time_needed = $request->time_needed;
        $service -> save();

        if($request->hasFile('image_url'))
        {
            $image = $request->file('image_url');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $service -> image_url = url('/public/uploads/') . '/' . $name;
            $service->save();
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Service added'
        ]);


    }

    public function get_services(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $service = SalonService::where('salon_id', $vendor->id)->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Service list',
            'data' => $service
        ]);

    }

    public function my_booking(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $appointment['new'] = Booking::where('salon_id',$vendor->id)
            ->where('status',1)
            ->with('user')
            ->get();
        $appointment['accepted'] = Booking::where('salon_id',$vendor->id)
            ->where('status',2)
            ->with('user')
            ->get();
        $appointment['finished'] = Booking::where('salon_id',$vendor->id)
            ->where('status',3)
            ->with('user')
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Salon booking list',
            'data' => $appointment
        ]);
    }

    public function accept_booking(Request $request)
    {
        $booking = Booking::where('id',$request->booking_id)->first();
        $booking -> status = 2;
        $booking -> save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'booking accepted',
        ]);

    }
    public function finish_booking(Request $request)
    {
        $booking = Booking::where('id',$request->booking_id)->first();
        $booking -> status = 3;
        $booking -> save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'booking finished',
        ]);

    }

    public function add_review(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $review = new Review();

        $review -> user_id = $request->user_id;
        $review -> salon_id = $vendor->id;
        $review -> rate = $request->rate;
        $review -> type = 2;

        $review -> save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'user rated',
        ]);

    }

    public function get_work_days(Request $request)
    {
        $vendor = Salon::where('user_id',Auth::id())->first();

        $data = SalonTime::where('salon_id',$vendor->id)->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'days list',
            'data' => $data
        ]);


    }

}
