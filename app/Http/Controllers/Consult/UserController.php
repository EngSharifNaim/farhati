<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Consult;
use App\Models\ConsultCategory;
use App\Models\ConsultService;
use App\Models\Notification;
use App\Models\Post;
use App\Models\Qa;
use App\Models\User;
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

    public function login(Request $request)
    {

        // check if email is exist in database
        $consult = Consult::where('mobile',$request->mobile)->first();
        if(!$consult)
        {
            return response([
                'code' => 200,
                'status' => true,
                'message' => 'mobile not exist',
            ]);
        }
        else{
            if($consult->active != 1)
            {
                $otp =mt_rand(2000,9000);

                return response([
                    'code' => 401,
                    'status' => false,
                    'message' => 'mobile not verified',
                    'otp' => $otp
                ]);
            }
//            return $login;
            if(Hash::check($request->password, $consult->password))
            {

                $accessToken = $consult->createToken('authToken')->accessToken;

                return response([
                    'code' => 200,
                    'status' => true,
                    'message' => 'Login successfully',
                    'user' => $consult,
                    'accessToken' => $accessToken
                ]);



            }
            else
            {

                return response()->json(['error' => ['Email and Password are Wrong.']], 200);


            }
        }
        // end check email

    }

    public function register(Request $request)
    {
        $consult = Consult::where('email',$request->email)->orWhere('mobile',$request->mobile)->first();
        $user = User::where('email',$request->email)->orWhere('mobile',$request->mobile)->first();
        if($consult || $user)
        {
            return response([
                'code' => 401,
                'status' => true,
                'message' => 'user is exist',
            ],401);

        }
        $data = $request->all();
        $otp =mt_rand(2000,9000);
        $data['password'] = Hash::make($data['password']);
        $consult = Consult::create($data);

        $caregories = ConsultCategory::all();
        foreach ($caregories as $caregory)
        {
            $services = new ConsultService();

            $services -> consult_id = $consult->id;
            $services -> name = $caregory->name;
            $services -> price = $caregory->cost;
            $services -> time = $caregory->time;
            $services -> currency = 'ريال';
            $services->save();
        }

        $database = app('firebase.firestore')->database();

        $var_list = $database->collection('users')->document($request->mobile)->set([
            'id' => $consult->id,
            'mobile' => $consult->mobile,
            'name' => $request->name,
            'imageUrl' =>  'http://almowasel.com/public/images/users/default.png'
        ]);

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'user registered successfully',
            'otp' => $otp
        ],201);
    }

    public function profile(Request $request)
    {

        $user = Consult::where('id',$request->consult_id)
            ->select('id','name','active','email','mobile','photo','country','prefix','gender','notes','experience_years','licence_type','licence_id','licence_from','licence_to','licence_country')
        ->first();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Consut profile',
            'data' => $user
        ]);
    }

    public function setProfile(Request $request)
    {
        $consult = Consult::where('id',$request->consult_id)->first();

        $consult->update($request->toArray());

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Consut profile',
            'data' => $consult
        ]);



    }
    public function verifyMobile(Request $request)
    {
        $consult = Consult::where('mobile',$request->mobile)->first();
        if($consult)
        {
            $consult->active = 1;
            $consult->save();

            return response([
                'code' => 200,
                'status' => true,
                'message' => 'User Activated successfully',
            ],200);

        }
    }

    public function chats(Request $request)
    {
        $chats = Chat::where('consult_id',$request->consult_id)
            ->with('user')
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'chat list',
            'data' => $chats
        ]);
    }
    public function notifications(Request $request)
    {
        $chats = Notification::where('consult_id',$request->consult_id)
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Notifications list',
            'data' => $chats
        ]);
    }

    public function search(Request $request)
    {
        $result['posts'] = Post::where('title','like','%' . $request->keyword . '%')->get();
        $result['consults'] = Consult::where('name','like','%' . $request->keyword . '%')->get();
        $result['qas'] = Qa::where('question','like','%' . $request->keyword . '%')->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'search results',
            'data' => $request
        ]);
    }

    public function setStatus(Request $request)
    {
        $consult = Consult::where('id',$request->consult_id)->first();

        if($consult->active == 1)
        {
            $consult->active = 0;
            $consult -> save();
            return response()->json([
                'code' => 200,
                'status' => 0,
            ]);

        }
        else
        {
            $consult->active = 1;
            $consult -> save();
            return response()->json([
                'code' => 200,
                'status' => 1,
            ]);


        }


    }

    public function setPhoto(Request $request)
    {
        $consult = Consult::where('id',$request->consult_id)->first();

        if($request->hasFile('photo'))
        {
            $image = $request->file('photo');
            $name =  time().'_' . mt_rand(2000,90000000) . '.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads');
            $image->move($destinationPath, $name);

            $consult -> photo = 'http://estishary.org/public/uploads/' . $name;

        }

        $consult->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'photo updated'
        ]);

    }

    public function getServices(Request $request)
    {
        $services = ConsultService::where('consult_id',$request->consult_id)->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'services list',
            'data' => $services
        ]);

    }

    public function setServiceStatus(Request $request)
    {
        $service = ConsultService::where('id',$request->service_id)->first();

        if($service->active == 1)
        {
            $service->active = 0;
        }
        else
        {
            $service->active = 1;
        }
        $service->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'services list',
            'active' => $service->active
        ]);


    }

    public function buildToken(Request $request)
    {
        $accessToken = new AccessToken2($request -> appId, $request -> appCertificate, $request -> expire);
        $serviceChat = new ServiceChat($request -> userId);

        $serviceChat->addPrivilege($serviceChat::PRIVILEGE_USER, $request -> expire);
        $accessToken->addService($serviceChat);

        return $accessToken->build();

    }

}
