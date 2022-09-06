<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Mail\ForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;
use Response; 
use Session; 
use Auth;
use Mail;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function reset(Request $request)
    {
        // dd($request->all());
        // $this->validate($request, [
        //     'otp' =>'required|numeric|min:6|max:6',
        //     'password' =>'required|string|min:6|required_with:password-confirm', 
        //     'password_confirm' =>'required|required_with:password|same:password',
        // ],
        // [   'otp.required'=>'OTP is required.',
        //     'password_confirm.same'=>'The confirm password and password must match.',
        //     'password_confirm.required'=>'The confirm password field is required']);

        $validator = Validator::make($request->all(), [
                'otp' =>'required|numeric|digits:6',
                'password' =>'required|string|min:6|required_with:password-confirm', 
                'password_confirm' =>'required|required_with:password|same:password',   
            ],
            [   'otp.required'=>'OTP is required.',
                'otp.digits'=>'Enter your 6 digits OTP.',
                'password_confirm.same'=>'The confirm password and password must match.',
                'password_confirm.required'=>'The confirm password field is required'
            ]
        );

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

        $chkOtp = User::where('remember_token',@$request->otp)->first();
        // dd($chkOtp);
        if(!@$chkOtp){
            $response['error']['message'] = "Invalid OTP please check !!";
            return Response::json($response);
             
        }
        else{
            if($request->password == $request->password_confirm && $request->password){
                 // dd('ok');
                $remember_token = $request->otp; 
                $update['password'] = Hash::make($request->password);
                $update['remember_token'] = '';
                $user = User::Where('remember_token',$remember_token)->update($update);
                if($user) {
                    return response()->json(['status'=>1,'message' =>'Password changed successfully !!'],200);
                     
                } else {
                    $response['error']['message'] = "Somthing went be wrong";
                    return Response::json($response); 
                }
            } else {
                $response['error']['message'] = "Password and Confirm Password not matched";
                return Response::json($response);  
            } 
        }

        


    }



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
