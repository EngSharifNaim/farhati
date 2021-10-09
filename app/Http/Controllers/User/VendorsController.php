<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Salon;
use App\Models\Vendor;
use App\Models\VendorReview;
use App\Models\Work;
use Illuminate\Http\Request;

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
    public function get_top_rated(Request $request)
    {
        $data['vendors'] = Vendor::where('status',1)
            ->get();
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
            ->withCount('reviews')
            ->with('products')
            ->with('reviews')
            ->with('reviews.user')
            ->first();
            $vendor->distance = 2.4;
            $vendor->isFavorit = false;

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
        $categories = Category::all();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'categories list',
            'data' => $categories
        ]);

    }
}
