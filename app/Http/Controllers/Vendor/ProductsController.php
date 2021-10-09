<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function get_product_by_category(Request $request)
    {
        $products = Product::where('category_id','like','%' . $request->category_id . '%')->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'products list',
            'data' => $products
        ]);

    }

    public function get_product_by_vendor(Request $request)
    {
        $products = Product::where('vendor_id',$request->vendor_id)
//            ->with('vendor')
            ->get();

        foreach ($products as $product)
        {
            $product->isFavorit = false;
            $product->isAvailable = false;
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'products list',
            'data' => $products
        ]);

    }

    public function get_my_products(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $products = Product::where('vendor_id',$vendor->id)
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'products list',
            'data' => $products
        ]);


    }

    public function add_product(Request $request)
    {
        $vendor = Vendor::where('user_id',Auth::id())->first();

        $data = $request->input();
        $data['vendor_id'] = $vendor->id;

        Product::create($data);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'products added',
        ]);

    }
}
