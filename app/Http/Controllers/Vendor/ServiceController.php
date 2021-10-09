<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::select('id','name','image','status')->get();

        return response()->json([
            'code' => 200,
            'message' => 'Services List',
            'data' => $services
        ]);
    }

}
