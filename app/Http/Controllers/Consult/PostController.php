<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Admin\Slider;
use App\Models\Post;
use App\Models\Qa;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class PostController extends Controller
{
    public function myPosts(Request $request)
    {
        $posts = Post::where('consult_id',$request->consult_id)
            ->get();
        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Consult posts',
            'data' => $posts
        ]);

    }

    public function addPost(Request $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->consult_id = $request->consult_id;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $post -> image = 'https://durarshop.com/consult/public/uploads/' . $name;

        }

        $post->save();

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'post added successfully',
        ]);

    }
    public function addQa(Request $request)
    {
        $post = new Qa();
        $post->question = $request->question;
        $post->answer = $request->answer;
        $post->consult_id = $request->consult_id;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $post -> image = 'https://durarshop.com/consult/public/uploads/' . $name;

        }

        $post->save();

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Question added successfully',
        ]);

    }
    public function myQas(Request $request)
    {
        $posts = Qa::where('consult_id',$request->consult_id)
            ->get();
        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Consult questions',
            'data' => $posts
        ]);

    }
}
