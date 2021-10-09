<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentFile;
use App\Models\Consult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
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

    public function store(Request $request)
    {
        $appointment = new Appointment();

        $appointment->user_id = Auth::id();
        $appointment->consult_id = $request->consult_id;
        $appointment->consult_category_id = $request->category_id;
        $appointment->service_id = $request->service_id;
        $appointment->notes = $request->notes;
        $appointment->consult_date = Carbon::now()->toDateString();
        $appointment->consult_time = Carbon::now()->toTimeString();

        $appointment->save();

        if($request->hasFile('files'))
        {
            $files = $request->file('files');
            foreach ($files as $file)
            {
                $attachment = new AppointmentFile();
                $attachment->appointment_id = $appointment->id;
                $image = $file;
                $name = rand(2000,9000000000).'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/attachments');
                $image->move($destinationPath, $name);
                $attachment -> file_url = 'attachments/' . $name;

                $attachment->save();

            }
        }

        $title = 'لديك حجز جديد';
        $body = 'تم وصول حجز جديد من: ' . Auth::user()->name;
        $token = Consult::where('id',$request->consult_id)->first()->fcm_token;
        $this->sen_notification($token,$title,$body,$appointment->id);

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'booking senr successfully'
        ]);
    }

    public function my_booking()
    {
        $appointment['new'] = Appointment::where('user_id',Auth::id())
            ->where('status',1)
            ->with('consult')
            ->with('service')
            ->with('category')
            ->get();
        $appointment['accepted'] = Appointment::where('user_id',Auth::id())
            ->where('status',2)
            ->orWhere('status',3)
            ->with('consult')
            ->with('service')
            ->with('category')
            ->get();
        $appointment['finished'] = Appointment::where('user_id',Auth::id())
            ->where('status',4)
            ->orWhere('status',5)
            ->with('consult')
            ->with('service')
            ->with('category')
            ->get();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'user booking list',
            'data' => $appointment
        ]);
    }

    public function booking_details(Request $request)
    {
        $appointment = Appointment::where('id',$request->booking_id)
            ->with('consult')
            ->with('service')
            ->with('category')
            ->with('files')
            ->first();
        foreach ($appointment->files as $file)
        {
            $file->file_url = 'http://estishary.org/public/' . $file->file_url;
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'user booking details',
            'data' => $appointment
        ]);

    }
}
