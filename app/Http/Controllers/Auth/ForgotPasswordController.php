<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Mail\ForgotPasswordMail;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Response; 
use Session; 
use Auth;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * This is for send forgot password link via email.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResetLinkEmail(Request $request)
    {  
        $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255','regex:/^\w+[-\.\w]*@(?!(?:myemail)\.com$)\w+[-\.\w]*?\.\w{2,4}$/'],   
            ]);

        if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

        $data['email'] = $request->email;
        $user = User::where('email',$request->email)->first();
        // dd($user);
        if(!@$user){
            $response['error']['message'] = "No record found.";
            return Response::json($response); 
        }
        
        $vcode = random_int(100000, 999999); 
        
        User::where('email',$request->email)->update(['remember_token'=>$vcode]);
        $data['OTP'] =  $vcode;
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['mailBody'] = 'forgotpassword';
        Mail::send(new ForgotPasswordMail($data));
        return response()->json(['status'=>1,'message' =>'A OTP send to your email address for reset your password .'],200);
        
    }
}
