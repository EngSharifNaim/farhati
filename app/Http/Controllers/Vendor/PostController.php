<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request){
        if($request->keyword)
        {
            $posts = Post::where('active',1)
                ->where('title','like','%'. $request->keyword . '%')
                ->with('consult')
                ->select('id','consult_id','title','content','created_at')
                ->get();

        }
        else
        {
            $posts = Post::where('active',1)
                ->with('consult')
                ->select('id','consult_id','title','content','created_at')
                ->get();

        }

        foreach ($posts as $post)
        {
            if(WishList::where('user_id',Auth::id())->where('post_id',$post->id)->first())
            {
                $post -> isFavorite = true;
            }
            else
            {
                $post -> isFavorite = false;

            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'posts List',
            'data' => $posts
        ]);

    }

    public function getPostDetails(Request $request)
    {
        $post = Post::where('id',$request->post_id)
            ->select('id','consult_id','title','content','image')
            ->with('consult')
            ->get();
        return response()->json([
            'code' => 200,
            'message' => 'posts details',
            'data' => $post
        ]);


    }

}
