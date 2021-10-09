<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
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
        $booking = new Booking();

        $booking->user_id = Auth::id();
        $booking->salon_id = $request->salon_id;
        $booking->products = $request->products;
        $booking->date = $request->booking_date;
        $booking->time = $request->booking_time;
        $booking->total_price = $request->total_price;

        $booking->save();


//        $title = 'لديك حجز جديد';
//        $body = 'تم وصول حجز جديد من: ' . Auth::user()->name;
//        $token = Consult::where('id',$request->consult_id)->first()->fcm_token;
//        $this->sen_notification($token,$title,$body,$appointment->id);

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'booking sent successfully'
        ]);
    }

    public function my_booking()
    {
        $appointment['new'] = Booking::where('user_id',Auth::id())
            ->where('status',1)
            ->with('salon')
            ->get();
        $appointment['accepted'] = Booking::where('user_id',Auth::id())
            ->where('status',2)
            ->with('salon')
            ->get();
        $appointment['finished'] = Booking::where('user_id',Auth::id())
            ->where('status',3)
            ->with('salon')
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
