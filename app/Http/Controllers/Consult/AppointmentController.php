<?php

namespace App\Http\Controllers\Consult;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class AppointmentController extends Controller
{
    public function home(Request $request)
    {
        $appointments = Appointment::where('consult_id',$request->consult_id)
            ->where('status',0)
            ->with('user')
            ->with('category')
            ->with('service')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id')
            ->get();

        $newAppointments = count($appointments);
        $totalConsult = Appointment::where('consult_id',$request->consult_id)
            ->where('status',3)
            ->count();

        $counters['new'] = $newAppointments;
        $counters['total'] = $totalConsult;
        $counters['profit'] = DB::table('appointments')
        ->join('consult_services','appointments.service_id','consult_services.id')
            ->where('appointments.status',3)
        ->sum('consult_services.price');

        $now =  date('Y-m-d',strtotime(Carbon::now()));

        $today['new'] = Appointment::where('consult_id',$request->consult_id)
            ->where('consult_date',$now)
            ->where('status',0)
            ->count();
        $today['done'] = Appointment::where('consult_id',$request->consult_id)
            ->where('consult_date',$now)
            ->where('status',3)
            ->count();
        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Consult home contents',
            'counters' => $counters,
            'today' => $today,
            'data' => $appointments
        ]);
    }
    public function appointments(Request $request)
    {
        $appointments['new'] = Appointment::where('consult_id',$request->consult_id)
            ->where('status',1)
            ->with('user')
            ->with('category')
            ->with('service')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id','status')
            ->get();
        $appointments['accepted'] = Appointment::where('consult_id',$request->consult_id)
            ->where('status',2)
            ->orWhere('status',3)
            ->with('user')
            ->with('category')
            ->with('service')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id','status')
            ->get();
        $appointments['finished'] = Appointment::where('consult_id',$request->consult_id)
            ->where('status',4)
            ->orWhere('status',5)
            ->with('user')
            ->with('category')
            ->with('service')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id','status')
            ->get();

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Consult home contents, 0 new, 1 accepted, 2 working, 3 finished, 4 rejected',
            'data' => $appointments
        ]);
    }
    public function appointmentsByDate(Request $request)
    {
        $appointments = Appointment::where('consult_id',$request->consult_id)
            ->where('consult_date',$request->date)
            ->where('status',0)
            ->with('user')
            ->with('category')
            ->with('service')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id','status')
            ->get();

        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Consult home contents',
            'data' => $appointments
        ]);
    }
    public function getAppointmentById(Request $request)
    {
        $appointments = Appointment::where('id',$request->appointment_id)
            ->with('user')
            ->with('category')
            ->with('service')
            ->with('files')
            ->select('id','consult_time','consult_date','consult_category_id','user_id','service_id','status','agora_token','channelName','uid','notes')
            ->first();

        foreach ($appointments->files as $file)
        {
            $file->file_url = 'http://estishary.org/public/' . $file->file_url;
        }


        return response([
            'code' => 200,
            'status' => true,
            'message' => 'Appointment details',
            'data' => $appointments
        ]);
    }

    public function rejectAppointment(Request $request)
    {
        $appointment = Appointment::where('id',$request->appointment_id)->first();
        $appointment -> status = 5;
        $appointment->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'appointment rejected',
            'data' => $appointment
        ]);
    }
    public function acceptAppointment(Request $request)
    {
        $appointment = Appointment::where('id',$request->appointment_id)->first();
        $appointment -> status = 2;
        $appointment->save();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'appointment accepted',
            'data' => $appointment
        ]);
    }
}
