<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class OrderConrtroller extends Controller
{
    public function add_direct_order(Request $request)
    {
        $data = $request->input();
        $data['user_id'] = Auth::id();

        DirectOrder::create($data);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'order sent successfully'
        ]);
    }
}
