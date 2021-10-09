<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function get_notifications(){
        $notifications=Notification::query()->where(['receiver_type'=>'User','receiver_id'=>Auth::id()])->get();
        if ($notifications->count() > 0){
            foreach ($notifications as $notification){
                $notification->data=json_decode($notification->data,true);
            }
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'notifications'=>$notifications,
                'code'=>200
            ]);
        }else{
            return response([
                'status'=>true,
                'message_en'=>'No notifications',
                'message_ar'=>'لا يوجد اشعارات',
                'code'=>200
            ]);
        }
    }
}
