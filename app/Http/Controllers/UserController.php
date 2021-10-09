<?php

namespace App\Http\Controllers;

use App\Imports\AccountImport;
use App\Models\Account;
use App\Models\MachineRead;
use App\Models\User;
use Illuminate\Http\Request;
use Excel;
use DB;
class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function employees()
    {
        return view('employees.index');

    }
    public function reads()
    {
        return view('reads.index');

    }
    public function import_accounts(Request $request)
    {
//        return $request->file('import_file');
        if($request -> hasFile('import_file')) {


            $path1 = $request->file('import_file')->store('temp');
            $path = storage_path('app') . '/' . $path1;

            \Excel::import(new AccountImport(), $path);

            \Session::put('success', 'لقد تم استشراد الكلف بنجاح.');
        }
        return back();
    }
    public function add_account(Request $request)
    {
        $data = $request->input();
        $account = Account::create($data);
        return back();
    }
    public function add_reads($month,$year)
    {
        $accounts = Account::all();
        foreach ($accounts as $account)
        {
            $read = new MachineRead();
            $read -> account_id = $account -> account_id;
            $read -> machine_id = $account -> machine_id;
            $read -> last_read = $account -> last_read;
            $read -> current_read = 0;
            $read -> status = 0;
            $read -> amount = 0;
            $read -> month = $month;
            $read -> year = $year;
            $read->save();
        }
        return response()->json([
            'status' => true
        ]);
    }

    public function get_accounts()
    {
        $accounts = Account::select('account_id','machine_id','customer_name','area','amount','last_read','status','type','updated_at')->get();
        return response()->json([
            'data' => $accounts
                ]
        );
    }
    public function get_employees()
    {
        $accounts = User::select('id','name','mobile','email')->where('type','employee')->get();
        return response()->json([
            'data' => $accounts
                ]
        );
    }
    public function get_reads()
    {
        $accounts = DB::table('machine_reads')
            ->join('accounts','accounts.account_id','machine_reads.account_id')
            ->select('accounts.customer_name',
                'accounts.account_id',
                'accounts.machine_id',
                'accounts.last_read',
                'machine_reads.status',
                'machine_reads.user_id',
                'machine_reads.current_read',
                'machine_reads.year',
                'machine_reads.month',
                'accounts.area')
            ->get();
        return response()->json([
            'data' => $accounts
                ]
        );
    }

}
