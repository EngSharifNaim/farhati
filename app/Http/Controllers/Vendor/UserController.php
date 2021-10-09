<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Consult;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Qa;
use App\Models\Salon;
use App\Models\Slider;
use App\Models\User;
use App\Models\Vendor;
use App\Models\WishList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\WebPushConfig;

use Google\Cloud\Firestore\FirestoreClient;
class UserController extends Controller
{
    public function sen_notification($token,$title,$body,$order_id)
    {
        $from = "AAAAwL5fhEI:APA91bHhFfF2e0dpSfFkcMuVrPnSFJxaKsez5WQ6bqu9kwiFd1dJ-s41mKhwDYy91GK5NGLbl2-SgJqAqM4XKBJyogHBGEYtouH4cQIXogcOvSzkrPNDj8_As58pTJPED3PtUineWMlR";

        $to = $token;

        $msg = array
        (
            'title' => $title,
            'body' => $body,

        );

        $fields = array
        (
            'to' => $to,
            'notification' => $msg,
            'data' => [
                'bookingId' => $order_id,
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "screen" =>  "POST_SCREEN",

            ]
        );
//            $arrayToSend = array('to' => "/topics/all", 'notification' => $notification, 'data' => $dataArr, 'priority'=>'high');
//            $fields = json_encode ($arrayToSend);


        $headers = array
        (
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

//            }

    }

    public function home()
    {
        $vendor = Vendor::where('user_id',Auth::id())
            ->with('services')
            ->with('services.category')
            ->first();

        $vendor->distance = 2.4;
        $vendor->isFavorit = false;
        $vendor->open = true;
        $vendor->work_time = '12:00 - 08:00';


        $caregories = Category::where('parent',0)->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'home page details',
            'vendor' => $vendor,
            'categories' => $caregories
        ]);
    }
    public function register(Request $request)
    {
        $user = User::where('email',$request->email)->orWhere('mobile',$request->mobile)->first();
        if($user)
        {
            return response([
                'code' => 401,
                'status' => true,
                'message' => 'user is exist',
            ],401);

        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user->save();

        $otp =mt_rand(2000,900000);
        $data['user_id'] = $user->id;

        $salon = Salon::create($data);
            $salon->save();


//        $database = app('firebase.firestore')->database();
//
//        $var_list = $database->collection('users')->document($request->mobile)->set([
//            'id' => $user->id,
//            'mobile' => $user->mobile,
//            'name' => $request->name,
//            'imageUrl' =>  'http://almowasel.com/public/images/users/default.png'
//        ]);

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'user registered successfully',
            'otp' => $otp
        ],200);
    }

    public function verify_mobile(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();
        $user->email_verified_at = Carbon::now();
        $user->save();
        return response([
            'code' => 200,
            'status' => true,
            'message' => 'user mobile verified',
        ],200);

    }
    public function send_otp(Request $request)
    {
        $otp =mt_rand(2000,9000);
        return response([
            'code' => 200,
            'status' => true,
            'message' => 'new OTP',
            'otp' => $otp
        ],200);

    }

    public function forgetPassword(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();

        if(!$user)
        {
            return response()->json([
                'code' => 404,
                'message' => 'mobile does not exist'
            ]);
        }

        else
        {
            $otp =mt_rand(2000,9000);
            return response()->json([
                'code' => 200,
                'message' => 'OTP Code',
                'otp' => $otp
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->first();

        if(!$user)
        {
            return response()->json([
                'code' => 404,
                'message' => 'mobile does not exist'
            ]);
        }

        else
        {
            $user -> password = Hash::make($request->password);
            $user ->save();
            return response()->json([
                'code' => 200,
                'message' => 'Password updated',

            ]);
        }
    }
    public function addToWishlist(Request $request)
    {
        if($request->consult_id)
        {
            $wish = WishList::where('user_id',Auth::id())
                ->where('consult_id',$request->consult_id)
                ->first();

            if($wish)
            {
                WishList::where('id',$wish->id)
                    ->delete();

                return response()->json([
                    'code' => 200,
                    'message' => 'posts deleted from wishlist',
                ]);
            }
            else
            {
                $wish = new WishList();
                $wish->consult_id = $request->consult_id;
                $wish->user_id = Auth::id();
                $wish->save();

                return response()->json([
                    'code' => 201,
                    'message' => 'posts added to wishlist',
                ]);

            }

        }
        if($request->post_id)
        {
            $wish = WishList::where('user_id',Auth::id())
                ->where('post_id',$request->post_id)
                ->first();

            if($wish)
            {
                WishList::where('id',$wish->id)
                    ->delete();

                return response()->json([
                    'code' => 200,
                    'message' => 'posts deleted from wishlist',
                ]);
            }
            else
            {
                $wish = new WishList();
                $wish->post_id = $request->post_id;
                $wish->user_id = Auth::id();
                $wish->save();

                return response()->json([
                    'code' => 201,
                    'message' => 'posts added to wishlist',
                ]);

            }

        }
    }

    public function getWishlist()
    {
        $withList['consult'] = WishList::where('user_id',Auth::id())
            ->where('consult_id','<>',NULL)
            ->with('consult')
            ->get();
        $withList['posts'] = WishList::where('user_id',Auth::id())
            ->where('post_id','<>',NULL)
            ->with('post')
            ->with('post.consult')
            ->get();
        $withList['qas'] = WishList::where('user_id',Auth::id())
            ->where('qa_id','<>',NULL)
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'posts added to wishlist',
            'data' => $withList
        ]);

    }

    public function show_data()
    {
        $user = Auth::user();

        if($user->show_data == 0)
        {
            $user -> show_data = 1;
            $user -> save();
            return response()->json([
                'code' => 200,
                'message' => 'show data enabled',
                'data' => $user->show_data
            ]);
        }
        else
        {
            $user -> show_data = 0;
            $user -> save();
            return response()->json([
                'code' => 200,
                'message' => 'show data disabled',
                'data' => $user->show_data
            ]);

        }
    }

    public function get_chat()
    {
        $chats = Chat::where('user_id',Auth::id())
            ->with('consult')
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'chats list',
            'data' => $chats
        ]);

    }

    public function profile()
    {
        return response()->json([
            'code' => 200,
            'message' => 'profile',
            'data' => Auth::user()
        ]);

    }

    public function setPhoto( Request $request){
        $user = User::where('id',Auth::id())->first();

        if($request->hasFile('photo'))
        {
            $image = $request->file('photo');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $user -> photo = 'http://estishary.org/public/uploads/' . $name;

        }

        $user->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'photo updated'
        ]);

    }

    public function search(Request $request)
    {
        if($request->type == 'post')
            $results = Post::where('title','like','%' . $request->keyword . '%')->get();
        if($request->type == 'consult')
            $results = Consult::where('name','like','%' . $request->keyword . '%')->get();
        if($request->type == 'qa')
            $results = Qa::where('question','like','%' . $request->keyword . '%')->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'search results',
            'data' => $results
        ]);
    }

    public function startSession(Request $request)
    {
        $appointment = Appointment::where('id',$request->appointment_id)->first();

        $appointment->agora_token = $request->agora_token;
        $appointment->channelName = $request->channelName;
        $appointment->uid = $request->uid;
        $appointment->status = 3;
        $appointment->save();

        $title = 'بدء لفاء الآن';
        $body = 'بدأ لقاء من قبل العميل: ' . Auth::user()->name;
        $token = Consult::where('id',$appointment->consult_id)->first()->fcm_token;
        $this->sen_notification($token,$title,$body,$appointment->id);


        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Token stored',
        ]);

    }

    public function change_password(Request $request)
    {
        $user = Auth::user();

        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'password updated'
        ]);

    }

    public function wishlist()
    {
        $list['vendors'] = WishList::where('user_id',Auth::id())
            ->with('vendor')
            ->where('vendor_id','<>',NULL)
            ->get();
        $list['products'] = WishList::where('user_id',Auth::id())
            ->with('product')
            ->where('product_id','<>',NULL)
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'wish list',
            'data' => $list
        ]);

    }

    public function add_to_wishlist(Request $request)
    {
        if($request->vendor_id)
        {
            $item = new WishList();
            $item -> user_id = Auth::id();
            $item -> vendor_id = $request->vendor_id;
            $item -> save();
        }

        if($request->product_id)
        {
            $item = new WishList();
            $item -> user_id = Auth::id();
            $item -> product_id = $request->product_id;
            $item -> save();
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'wishlist updated'
        ]);


    }
    public function delete_from_wishlist(Request $request)
    {
        if($request->vendor_id)
        {
            WishList::where('user_id',Auth::id())
            ->where('vendor_id',$request->vendor_id)
            ->delete();
        }

        if($request->product_id)
        {
            WishList::where('user_id',Auth::id())
                ->where('product_id',$request->product_id)
                ->delete();
        }

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'wishlist updated'
        ]);


    }

    public function get_notifications()
    {
        $nots = Notification::where('user_id',Auth::id())->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'notifications',
            'data' => $nots
        ]);

    }

}
