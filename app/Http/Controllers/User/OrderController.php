<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DirectOrder;
use App\Models\User\Notification;
use App\Models\User\Offer;
use App\Models\User\Order;
use App\Models\User\OrderDetail;
use App\Models\User\OrderService;
use App\Models\User\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    function send_notification($fcm_token,$title,$body)
    {
        $from = "AAAAWZq48UE:APA91bEIJ3IykElWeje0fE_nM9WSXUWA_bt8iXBI6LYzt8Y8JvDJCzKlSSUVaITIwDmdgTYr_B6DwkWfEnnYHrA_1mL8rlyZZzHN07jGZV_O9O6eDS-C972b2V78QY4rcs78MfOj4dgm";
        $to= $fcm_token;
        $msg = array
        (
            'body'  => $body,
            'title' => $title,
        );

        $fields = array
        (
            'to' => $to,
            'notification'  => $msg
        );

        $headers = array
        (
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );


    }
    public function add_direct_order(Request $request)
    {
        $data = $request->input();
        $data['user_id'] = Auth::id();

        DirectOrder::create($data);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'order sent successfully'
        ]);
    }
    public function add_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'city_id'=>'required',
            'out_city'=>'required',
            'event_date'=>'required',
            'event_time'=>'required',
//            'services_list'=>'required',

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
        if($validator->passes()){
            DB::beginTransaction();
            try {
                if(isset($request->services_list)){
                    $services=json_decode($request->services_list,true);
                }

                $data_order=[
                    'user_id'=>Auth::id(),
                    'title'=>$request->title,
                    'city_id'=>$request->city_id,
                    'out_city'=>$request->out_city,
                    'event_date'=>$request->event_date,
                    'event_time'=>$request->event_time,
                ];
                if(isset($request->event_id)){
                    $data_order['event_id']=$request->event_id;
                    $data_order['type']=2;
                }
//                return $data_order;
                $order=Order::query()->create($data_order);
                $data_order_details=[
                    'order_id' => $order->id,
                    'offer_date_from'=>$request->offer_date_from,
                    'offer_time_from'=>$request->offer_time_from,
                    'offer_date_to'=>$request->offer_date_to,
                    'offer_time_to'=>$request->offer_time_to,
                ];
                OrderDetail::query()->create($data_order_details);
                if(isset($request->services_list)) {
                    $services_list=[];
                    foreach ($services as $service) {
                        $services_list[]=$service['category_id'];
                        OrderService::query()->create([
                            'order_id' => $order->id,
                            'category_id' => $service['category_id'],
                            'notes' => $service['notes'],
                        ]);
                    }
//                    return $services_list;
                    $vendors=Vendor::query()->where(['status'=>1])
                      ->get();
                    foreach ($vendors as $vendor){
                        $services_list_vendor=  json_decode($vendor->category_id,true);
                        $result = !empty(array_intersect($services_list, $services_list_vendor));
                      if ($result ==1){
                          Notification::query()->create([
                              'sender_id'=>Auth::id(),
                              'sender_type'=>'User',
                              'receiver_id'=>$vendor->id,
                              'receiver_type'=>'Vendor',
                              'data'=>'[{"title":"New Order By'. Auth::user()->name .'","order_id":"'.$order->id.'"}]',
                          ]);

                          $fcm_token = $vendor->fcm_token;
                          $title = 'لقد وصلك طلب جديد';
                          $body = 'اسم العميل :  '. Auth::user()->name;
                          $this->send_notification($fcm_token,$title,$body);
                      }

                    }
                }
                if (isset($request->event_id)){

                    $vendors=Vendor::query()->where(['status'=>1])->get();

                    foreach ($vendors as $vendor){
                          $events=json_decode($vendor->event_list,true);
                        if (in_array($request->event_id,$events)){
                            Notification::query()->create([
                                'sender_id'=>Auth::id(),
                                'sender_type'=>'User',
                                'receiver_id'=>$vendor->id,
                                'receiver_type'=>'Vendor',
                                'data'=>'[{"title":"New Order By'. Auth::user()->name .'","order_id":"'.$order->id.'"}]',
                            ]);

                            $fcm_token = $vendor->fcm_token;
                            $title = 'لقد وصلك طلب جديد';
                            $body = 'اسم العميل :  '. Auth::user()->name;
                            $this->send_notification($fcm_token,$title,$body);
                        }
                    }
                }




                DB::commit();
                return response([
                    'status'=>true,
                    'message_en'=>'operation accomplished successfully',
                    'message_ar'=>'تمت العملية بنجاح',
                    'order_id'=>$order->id,
                    'code'=>200
                ]);
            }
            catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }
    public function my_orders(){
        $orders=Order::query()->where(['user_id'=>Auth::id(),'type'=>1])
            ->with('order_categories')
            ->withCount('order_categories')
            ->get();
        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'orders'=>$orders,
            'code'=>200
        ]);
    }
    public function my_events(){
        $orders=Order::query()->where(['user_id'=>Auth::id(),'type'=>2])
            ->with('event')

            ->get();
        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'orders'=>$orders,
            'code'=>200
        ]);
    }
    public function my_direct_orders(){
        $orders=DirectOrder::query()->where(['user_id'=>Auth::id()])->get();
        return response([
            'status'=>true,
            'message_en'=>'operation accomplished successfully',
            'message_ar'=>'تمت العملية بنجاح',
            'orders'=>$orders,
            'code'=>200
        ]);
    }
    public function order_by_id($order_id){
        $orders=Order::query()->where(['id'=>$order_id,'type'=>1])
            ->with('order_categories')
            ->withCount('order_categories')
            ->first();
        if ($orders) {
            return response([
                'status' => true,
                'message_en' => 'operation accomplished successfully',
                'message_ar' => 'تمت العملية بنجاح',
                'orders' => $orders,
                'code' => 200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'This order is not available',
                'message_ar'=>'هذا الطلب غير موجود',
                'code'=>404
            ]);
        }
    }
    public function event_by_id($event_id){
        $orders=Order::query()->where(['id'=>$event_id ,'type'=>2])
            ->with('event')
            ->first();

        if ($orders){
            $offers=Offer::query()->where(['order_id'=>$orders->id])
                ->with('vendor')
                ->get();
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'orders'=>$orders,
                'offers'=>$offers,
                'code'=>200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'This event is not available',
                'message_ar'=>'هذه المناسبة غير موجودة',
                'code'=>404
            ]);
        }

    }
    public function offers_by_category_id($order_id,$category_id){
        $offers=Offer::query()->where(['category_id'=>$category_id,'order_id'=>$order_id])
            ->with('vendor')
            ->get();
        if ($offers->count() >0){
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'offers'=>$offers,
                'code'=>200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'There are no offers for this category',
                'message_ar'=>'لا يوجد عروض لهذا التصنيف',
                'code'=>404
            ]);
        }

    }
    public function offers_by_event_id($order_id,$event_id){
        $offers=Offer::query()->where(['category_id'=>$event_id,'order_id'=>$order_id])
            ->with('vendor')
            ->get();
        if ($offers->count() >0){
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'offers'=>$offers,
                'code'=>200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'There are no offers for this category',
                'message_ar'=>'لا يوجد عروض لهذا التصنيف',
                'code'=>404
            ]);
        }

    }
    public function accept_offer($offer_id){
        $offer=Offer::query()->where(['id'=>$offer_id])
            ->first();
        if ($offer){
            $offer->update([
                'status'=>1
            ]);
            Offer::query()->where(['order_id'=>$offer->order_id,'category_id'=>$offer->category_id])
                ->whereNotIn('id',[$offer->id])
                ->update(
                [
                    'status'=>2,

                ]
            );

            Order::query()->where('id',$offer->order_id)->update([
                'vendor_id'=>$offer->vendor_id,
                'accept_offer'=>$offer->id
            ]);
            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'code'=>200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'This offer is not available',
                'message_ar'=>'هذا العرض غير موجود',
                'code'=>404
            ]);
        }

    }
    public function accept_direct_order($order_id){
        $order=DirectOrder::query()->where(['id'=>$order_id,'user_id'=>Auth::id(),'status'=>4])
            ->first();
        if ($order){
//            return $order;
            $order->update([
                'status'=>2,
            ]);
//            return $order;

            return response([
                'status'=>true,
                'message_en'=>'operation accomplished successfully',
                'message_ar'=>'تمت العملية بنجاح',
                'code'=>200
            ]);
        }
        else{
            return response([
                'status'=>true,
                'message_en'=>'This Order is not available',
                'message_ar'=>'هذا الطلب غير موجود',
                'code'=>404
            ]);
        }

    }

}
