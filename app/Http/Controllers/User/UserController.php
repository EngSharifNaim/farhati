<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Imports\AccountImport;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Consult;
use App\Models\MachineRead;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Qa;
use App\Models\Slider;
use App\Models\User;
use App\Models\WishList;
use App\Models\User\Favorite;
use App\Models\User\Vendor;
use App\Models\User\VendorRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;
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
        $data['main_slider'] = Slider::where('main',1)->get();
        $data['sliders'] = Slider::where('main',0)->get();
        $data['categories'] = Category::all();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'home page details',
            'data' => $data
        ]);
    }
    public function register(Request $request)
    {
        $user = User::where('mobile',$request->mobile)->orWhere('email',$request->email)->first();
        if($user)
        {
            return response([
                'code' => 401,
                'status' => true,
                'message' => 'user is exist',
            ],401);

        }
        $data = $request->all();
        $otp =mt_rand(2000,900000);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

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
        ],201);
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

    public function get_sliders(Request $request)
    {
        $sliders = Slider::all();
        return response()->json([
            'code' => 200,
            'message' => 'sliders',
            'data' => $sliders
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

    public function get_profile()
    {
        return response()->json([
            'code' => 200,
            'message' => 'profile',
            'data' => Auth::user()
        ]);

    }
    public function set_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'mobile'=>'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'status'=>false,
                'message_en'=>'Make sure that the information is correct and fill in all fields',
                'message_ar'=>'تاكد من صحة البيانات وملئ جميع الحقول',
                'errors'=>$errors,
                'code'=>422
            ]);
        }
        if ($validator->passes()){
            $data = $request->all();
            $user = Auth::user();
            $user -> update($data);
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'data'=>Auth::user(),
                'code'=>200
            ]);
        }


    }

    public function set_photo( Request $request){
        $validator = Validator::make($request->all(), [
            'photo'=>'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'status'=>false,
                'message_en'=>'Make sure that the information is correct and fill in all fields',
                'message_ar'=>'تاكد من صحة البيانات وملئ جميع الحقول',
                'errors'=>$errors,
                'code'=>422
            ]);
        }
        if ($validator->passes()){
            $user = User::query()->where('id',Auth::id())->first();
            $data = $request->all();
            DB::beginTransaction();
            try{
                if ($request->hasfile('photo') ) {
                    $image_user = $request->file('photo');
                    $image_name = 'public/uploads/users/'.time().'.' .$image_user->getClientOriginalExtension();
                    $image_user->move(public_path('uploads/users/'), $image_name);

                    $data['photo'] = $image_name;
                    $oldImage=public_path('uploads/users/'). explode('/',$user->photo)[3];
                    if ($oldImage != public_path('uploads/users/').'default-user-image.png'){
                        unlink($oldImage);
                    }
    //                return $data;
                } else {
                    unset($data['photo']);
                }
//            return $data;
            $user->update($data);


                DB::commit();
                return response([
                    'status'=>true,
                    'message_en'=>'operation accomplished successfully',
                    'message_ar'=>'تمت العملية بنجاح',
                    'data'=>$user,
                    'code'=>200
                ]);
            }
            catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }


    }

    public function get_vendor_by_service(Request $request)
    {

            $vendors = Vendor::query()->where(['status'=>1])
                ->get();
            $results=[];
            foreach ($vendors as $vendor){
                $services_list=json_decode($vendor->category_id);
                if (in_array($request->service_id,$services_list)){
                    $results[]=$vendor;
                }
            }

        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'offers'=>$results,
            'code'=>200
        ]);
    }
    public function get_vendor_by_keyword(Request $request)
    {

            $results = Vendor::query()->where([['name','like','%' . $request->keyword . '%'],'status'=>1])->get();

        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'offers'=>$results,
            'code'=>200
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
        $validator = Validator::make($request->all(), [
            'password'=>'required',

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'status'=>false,
                'message_en'=>'Make sure that the information is correct and fill in all fields',
                'message_ar'=>'تاكد من صحة البيانات وملئ جميع الحقول',
                'errors'=>$errors,
                'code'=>422
            ]);
        }
        if ($validator->passes()){
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'code'=>200
            ]);


        }

    }

    public function my_favorites()
    {
        $list = Favorite::query()->where('user_id',Auth::id())
            ->with('vendor')
            ->get();

            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'data'=>$list,
                'code'=>200
            ]);



    }

    public function add_to_favorite(Request $request)
    {
        $item = Favorite::query()->where(['vendor_id'=>$request->vendor_id,'user_id'=>Auth::id()])
            ->first();
        if($item)
        {
            $item->delete();

        }
        else
        {
            Favorite::query()->create([
                'vendor_id'=>$request->vendor_id,
                'user_id'=>Auth::id(),
            ]);
        }

        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'code'=>200
        ]);


    }
    public function add_vendor_rate(Request $request)
    {
        $item = VendorRate::query()->where(['vendor_id'=>$request->vendor_id,'user_id'=>Auth::id()])
            ->first();
        if($item)
        {
            return response([
                'status'=>true,
                'message_en'=>'Rating can not be more than once',
                'message_ar'=>'لا يمكن التقييم اكثر من مرة',
                'code'=>404
            ]);

        }
        else
        {
            VendorRate::query()->create([
                'vendor_id'=>$request->vendor_id,
                'user_id'=>Auth::id(),
                'comment'=>$request->comment,
                'rate'=>$request->rate,
            ]);
            $vendor_rate=VendorRate::query()->where('vendor_id',$request->vendor_id)->get();
            if($vendor_rate->count() >0) {
                $count = $vendor_rate->count();
                $sum = $vendor_rate->sum('rate');
                $total_rate = ($sum / $count);
                Vendor::query()->where('id',$request->vendor_id)->update([
                    'rate'=>$total_rate
                ]);
            }
            else{
                Vendor::query()->where('id',$request->vendor_id)->update([
                    'rate'=>0
                ]);
            }

            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'code'=>200
            ]);
        }




    }
    public function get_rates_by_vendor_id($vendor_id){
        $rates = VendorRate::query()->where('vendor_id',$vendor_id)
            ->with('user')
            ->get();

        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'data'=>$rates,
            'code'=>200
        ]);
    }
    public function delete_from_favorite($favorite_id)
    {
        $favorite=Favorite::query()->where('id',$favorite_id)->first();
        if($favorite)
        {
            $favorite->delete();
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'code'=>200
            ]);
        }else{
            return response([
                'status'=>true,
                'message_en'=>'Never been to my favourites',
                'message_ar'=>'لا يوجد في المفضلة من قبل',
                'code'=>404
            ]);
        }



    }







}
