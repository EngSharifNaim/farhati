<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

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
}
