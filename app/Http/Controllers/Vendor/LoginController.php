<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {

        // check if email is exist in database
        $user = User::where('mobile',$request->mobile)->first();
        if(!$user)
        {
            return response([
                'code' => 200,
                'status' => true,
                'message' => 'mobile not exist',
            ]);
        }
        else{
            if($user->email_verified_at == NULL)
            {
                return response([
                    'code' => 401,
                    'status' => false,
                    'message' => 'mobile not verified'
                ]);
            }
            $login = ['mobile' => $request->mobile,'password' => $request->password];
//            return $login;
            if(!Auth::attempt($login))
            {
                return response([
                    'code' => 401,
                    'status' => false,
                    'message' => 'Login failed'
                ]);
            }
            else
            {

                $accessToken = Auth::User()->createToken('authToken')->accessToken;

                return response([
                    'code' => 200,
                    'status' => true,
                    'message' => 'Login successfully',
                    'user' => Auth::User(),
                    'accessToken' => $accessToken
                ]);

            }
        }
        // end check email

    }

}
