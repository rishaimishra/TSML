<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\User;
use App\Admin;
use App\Address;
use JWTAuth;
use Validator;
 
class AuthController extends Controller
{
  public function register(Request $request)
  {
      // dd($request->all());
      // jwt key -  i4qBSBNxEQ6XHhJcykjX2PxeXmB9a1nVhZwMlXz4fwsBwCBCoKmkxDEhrXTeUHo0
      $validator = Validator::make($request->all(), [
          'email' => 'required|string|email|max:255|unique:users',
          'name' => 'required',
          'phone' => 'required',
          'password'=> 'required'
      ]);
      if ($validator->fails()) {
          return response()->json($validator->errors());
      }
      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->phone = $request->phone;
      $user->password = bcrypt($request->password);
      $user->save();
      $user = User::first();
      $token = JWTAuth::fromUser($user);

      // dd($token);

      return response()->json([
          'success' => true,
          'token' => $token,
      ]);
  }

   /**
    * Get a JWT via given credentials.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function login(Request $request)
   {
       // $credentials = request(['email', 'password']);
 
       // if (! $token = auth()->attempt($credentials)) {
       //     return response()->json(['error' => 'Unauthorized'], 401);
       // }
 
       // return $this->respondWithToken($token);

       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
 
        $input = $request->only('email', 'password');
        $jwt_token = null;
        
        // dd($jwt_token);
   
        if (!$jwt_token = JWTAuth::attempt($input)) {
            // dd($jwt_token);
            $chkuser = User::where('email',$request->email)->first();
            // dd($chkusermail);
            // \Hash::check($request->password, $user->password)
            $chkuserpass  = \Hash::check($request->password, $chkuser->password);
            // dd($chkuserpass);
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
        
        $authChk = Auth::user()->is_loggedin;
        // dd($id);
        if($authChk == 0)
        {
            $userArr['user_id'] = Auth::user()->id;
            $userArr['user_name'] = Auth::user()->name;
            $userArr['user_type'] = Auth::user()->user_type;
            $updata['is_loggedin'] = 1;
            $upuser = User::where('id',Auth::user()->id)->update($updata);
            return response()->json([
                'success' => true,
                'data' => $userArr,
                'token' => $jwt_token,
            ]);
      }else{
          
           return response()->json([
                'success' => false,
                'message' => 'You are already logged in, please logout from there',
            ]);

      }
   }
 
   /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function me()
   {
       $response = [];
       $response['customer_information'] = User::select('name','email','phone')->where('id',auth()->user()->id)->first();
       $response['customer_kyc'] = User::select('company_gst','company_pan')->where('id',auth()->user()->id)->first();
       $response['shipping_address'] = Address::where('user_id',auth()->user()->id)->where('type','A')->get();
       $response['billing_address'] = Address::where('user_id',auth()->user()->id)->where('type','B')->get();
       $response['documents'] =  User::select('address_proof_file','cancel_cheque_file','pan_card_file','gst_certificate','turnover_declare','itr_last_yr','form_d','registration_certificate','tcs')->where('id',auth()->user()->id)->first();
       $response['file_link'] = asset('https://beas.in/mje-shop/storage/app/public/user');
       $response['success'] = true;
       return $response;
   }

    public function updateMobile(Request $request)
   {
        $response = [];
        $validator = Validator::make($request->all(), [
            'mobile'=>'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        User::where('id',auth()->user()->id)->update(['phone'=>$request->mobile]);
        $response['success'] = true;
        $response['message'] = 'Mobile Number Updated Successfully';
        return $response;
   }
 
   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {

       $updata['is_loggedin'] = 0;
       $upuser = User::where('id',Auth::user()->id)->update($updata);
       auth()->logout();
       
       return response()->json(['message' => 'Successfully logged out']);
   }
 
   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh()
   {
       return $this->respondWithToken(auth()->refresh());
   }
 
   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60
       ]);
   }


   public function updateLoggedin(Request $request)
   { 

         $id = $request->input('id');
         $val = $request->input('value');
        $res = User::where('id',$id)->update(['is_loggedin' => $val]);

        return response()->json([
           'status' => 1,
           'message' => 'updated'
       ]);
   }


 
}