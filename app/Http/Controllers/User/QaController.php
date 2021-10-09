<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Qa;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QaController extends Controller
{
    public function index(Request $request){

        if($request->keyword)
        {
            $qas = Qa::where('active',1)
                ->where('question','like','%' . $request->keyword . '%')
                ->with('consult')
                ->get();
        }
        else
        {
            $qas = Qa::where('active',1)
                ->with('consult')
                ->get();
        }

        foreach ($qas as $qa)
        {
            if(WishList::where('user_id',Auth::id())->where('qa_id',$qa->id)->first())
            {
                $qa -> isFavorite = true;
            }
            else
            {
                $qa -> isFavorite = false;

            }
        }
        return response()->json([
            'code' => 200,
            'message' => 'Questions List',
            'data' => $qas
        ]);

    }

}
