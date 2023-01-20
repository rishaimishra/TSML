<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\OtpVerification;
use App\Mail\Register;
use App\ServicesMy\MailService;
use App\User;
use JWTAuth;
use Validator;
use Response; 
use Mail;
use DB;

 
class UserController extends Controller
{
     
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function test(Request $request)
   {  
        $usersdata = DB::table('users')
                    ->select('users.state')
                    ->groupBy('users.state')
                    ->get();
                    foreach ($usersdata as $key => $userval) 
                    {
                        if (isset($userval->state)) {
                           $getlocation[] = $userval->state;
                        }
                        
                    }

        echo "<pre>";
        print_r($getlocation);
   }

   public function testmail()
   {     
    phpinfo();
    // $data['email'] = 'partha.beas@gmail.com';
    //     Mail::send(new Register($data));

    //  echo "mail sent - ".$data['email'];
   }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $users = User::all();

       $userslist = [];

       foreach ($users as $key => $value) {

        $userdata['name'] = $value->name;
	    $userdata['email'] = $value->email; 
	    $userdata['phone'] = $value->phone;
		$userdata['gstin'] = $value->gstin;
		$userdata['org_address'] = $value->org_address;
		            
        if($value->address_proof_file) 
        {

            $userdata['address_proof_file_url'] = asset('storage/app/public/user/'.$value->address_proof_file);
        }
        else
           {
               $userdata['address_proof_file_url'] =  null;
           }

           $userslist[] = $userdata;

       }

       
       return $userslist;
   }

    
    /**
     * This is for validate user mobile number via OTP.
     *
     * @return \Illuminate\Http\Response
    */
    public function sendOtpToMobile(Request $request)
    {
        
        $validator = Validator::make($request->all(), [ 
            'mobile_no' =>'required|digits:10',
            'email' => ['required', 'string','max:255','regex:/^\w+[-\.\w]*@(?!(?:myemail)\.com$)\w+[-\.\w]*?\.\w{2,4}$/'],
              
        ],
        [   
            'mobile_no.required'=>'Mobile is required',               
        ]
        );
        // dd($request->all());
        if ($validator->fails()) {
            $response['error']['validation'] = $validator->errors();
            return Response::json($response);
        }

        $chkmob = OtpVerification::where('email',$request->email)->where('mob_number',$request->mobile_no)->first(); 

        // dd($chkmob);
        if(!empty($chkmob->otp) && $chkmob->is_verified != 2)
        {
            return response()->json(['status'=>0,'message' => array('OTP already send to this email addess '.$request->email)]); 
        }
        else
        {

            if(isset($chkmob->otp) && $chkmob->is_verified == 2)
            {
                return response()->json(['status'=>0,'message' => array('Your mobile number already verified.')]);
            }
            else
            {
                $otp = random_int(100000, 999999); 

                $input['mob_number'] = $request->mobile_no;
                $input['email'] = $request->email;
                $input['otp'] = $otp;

                $categoryData = OtpVerification::create($input);  

                $sub = "OTP for registration";
                $html = 'mail.Otpverificationmail';
                $data['otp'] = $otp;
                $cc_email = "";
                $email = $request->email;

                (new MailService)->dotestMail($sub,$html,$email,$data,$cc_email); 
       
                $msg = "OTP has been send to this email address ".$request->email." successfully.";
                $userdata['mob_number'] = $request->mobile_no;
                $userdata['email'] = $request->email;
                return response()->json(['status'=>1,'message' =>$msg,'result' =>$userdata],200);
            }
            
           
            
        }

        
    }

    /**
     * This is for validate user mobile OTP.
     *
     * @return \Illuminate\Http\Response
    */
    public function verifyMobileOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'mobile_no' =>'required|digits:10',
            'email' =>'required|email',
            'email' => ['required', 'string','max:255','regex:/^\w+[-\.\w]*@(?!(?:myemail)\.com$)\w+[-\.\w]*?\.\w{2,4}$/'], 
            'otp' =>'required|digits:6',              
        ],
        [   
            'mobile_no.required'=>'Mobile is required',
            'otp.required'=>'OTP is required',               
        ]
        );

        if ($validator->fails()) {
            $response['error']['validation'] = $validator->errors();
            return Response::json($response);
        }

        $chkmob = OtpVerification::where('email',$request->email)->where('mob_number',$request->mobile_no)->first();
        // dd($chkmob);
        if (!empty($chkmob)) 
        {
            if($chkmob->otp == null && $chkmob->is_verified == 2)
            {
                return response()->json(['status'=>0,'message' => array('Your mobile number already verified.')]); 
            }
            else
            {
                if(!empty($chkmob->otp) && $chkmob->is_verified != 2)
                {
                    if ($chkmob->otp == $request->otp) 
                    {
                        $input['is_verified'] = 2;
                        $input['otp'] = '';

                        $categoryData = OtpVerification::where('mob_number',$request->mobile_no)->where('otp',$chkmob->otp)->update($input); 
                 
                        return response()->json(['status'=>1,'message' =>'Verification successfully.','result' => $chkmob],200);
                    }
                    else
                    {
                        return response()->json(['status'=>0,'message' => array('Invalid OTP please check')]);
                    }

                }
                else
                {
                    return response()->json(['status'=>0,'message' => array('OTP already send to this mobile number '.$request->mobile_no)]);
                }

            }

        }
        else
        {
            return response()->json(['status'=>0,'message' => array('Somthing wrong please check.')]);
        }

        
         

    }

   /**
     /**
     * This is for change user password .
     *
     * @return \Illuminate\Http\Response
    */
    public function resetPassword(Request $request)
    {

        
        $token = request()->bearerToken();

        if(isset($token))
        {
            $user = JWTAuth::setToken($token)->toUser();

            if (!empty($user)) {
                $user = JWTAuth::setToken($token)->toUser();
 

                // $validator = Validator::make($request->all(), [ 
                //     'current_password' =>'required|string|min:6',
                //     'password' =>'required|string|min:6|required_with:password-confirm', 
                //     'password_confirm' =>'required|required_with:password|same:password',   
                // ],
                // [   
                //     'current_password.required'=>'The current password field is required',
                //     'password_confirm.same'=>'The confirm password and password must match.',
                //     'password_confirm.required'=>'The confirm password field is required'
                // ]
                // );
                $validator = Validator::make($request->all(), [ 
                    'current_password' =>'required|string|min:6',
                    'password' =>'required|string|min:6',  
                ],
                [   
                    'current_password.required'=>'The current password field is required',
                    'password.required'=>'The password field is required.', 
                ]
                );

                if ($validator->fails()) {
                    $response['error']['validation'] = $validator->errors();
                    return Response::json($response);
                }

                 

                if (!empty($request->current_password) && !empty($user)) 
                {
                    
                    if (\Hash::check($request->current_password, $user->password)) 
                    {

                        $input['password'] = \Hash::make($request->password);
                        $saveuser = User::where('id',$user->id)->update($input);
                        return response()->json(array("status"=>1,"message"=>"Password change successfully ."));
                         
                    }
                    else 
                    {
                        return response()->json(['status'=>0,'message' => 'Current  password does not match please try again!']); 
                        
                    }
                }
                else 
                {
                    return response()->json(['status'=>0,'message' => 'User not found!']); 
                    
                } 

                
            }
            else{
                $response['error']['message'] = "No data found.";
                return Response::json($response);
            }
            
        }
        else{
            $response['error']['message'] = "Bearer token not found.";
            return Response::json($response);
        }
        
         


    }
 
   /**
    * Store a newly created resource in storage.
    *
    */
   public function store(Request $request)
   {
        try {

            $validator = Validator::make($request->all(), [
                // 'name'        => 'required', 
                'email'        => 'required|unique:users,email',   
                'password'     => 'required',  
                // 'address_proof_file' => 'required|mimes:jpg,jpeg,png,bmp',
                'phone'        => 'required|unique:users,phone',
                'company_pan'  => 'required|unique:users,company_pan', 
                'company_gst'  => 'required', 
                // 'first_name'  => 'required', 
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            // $userData = $request->all();
            $userData = [];
            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['phone'] = $request->phone;
            $userData['password'] = $request->password;
            $userData['gstin'] = $request->gstin;
            $userData['org_pan'] = $request->org_pan;
            $userData['org_name'] = $request->org_name;
            $userData['org_address'] = $request->org_address;
            $userData['pref_product'] = $request->pref_product;
            $userData['pref_product_size'] = $request->pref_product_size;
            $userData['user_type'] = $request->user_type;
            $userData['company_gst'] = $request->company_gst;
            $userData['company_linked_address'] = $request->company_linked_address;
            $userData['company_pan'] = $request->company_pan; 
            $userData['company_name'] = $request->company_name;
            $userData['business_nature'] = $request->business_nature; 
            $userData['is_tcs_tds_applicable'] = $request->is_tcs_tds_applicable; 
            $userData['first_name'] = $request->first_name; 
            $userData['last_name'] = $request->last_name; 
            $userData['addressone'] = $request->addressone; 
            $userData['addresstwo'] = $request->addresstwo; 
            $userData['city'] = $request->city; 
            $userData['state'] = $request->state; 
            $userData['pincode'] = $request->pincode; 
            $userData['address_type'] = $request->address_type;            
            $userData['pan_dt'] = $request->pan_dt; 
            $userData['gst_dt'] = $request->gst_dt; 
            $userData['formD_dt'] = $request->formD_dt; 
            $userData['tcs_dt'] = $request->tcs_dt;

            if ($request->hasFile('address_proof_file'))
            {
             $image = $request->address_proof_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['address_proof_file'] = $filename;
            }


            if ($request->hasFile('cancel_cheque_file'))
            {
             $image = $request->cancel_cheque_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['cancel_cheque_file'] = $filename;
            }


            if ($request->hasFile('pan_card_file'))
            {
             $image = $request->pan_card_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['pan_card_file'] = $filename;
            }


            if ($request->hasFile('gst_certificate'))
            {
             $image = $request->gst_certificate;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['gst_certificate'] = $filename;
            }


            if ($request->hasFile('turnover_declare'))
            {
             $image = $request->turnover_declare;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['turnover_declare'] = $filename;
            }


            if ($request->hasFile('itr_last_yr'))
            {
             $image = $request->itr_last_yr;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['itr_last_yr'] = $filename;
            }
            
            
            if ($request->hasFile('form_d'))
            {
             $image = $request->form_d;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['form_d'] = $filename;
            }
            
            
            if ($request->hasFile('registration_certificate'))
            {
             $image = $request->registration_certificate;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['registration_certificate'] = $filename;
            }
            
            
            if ($request->hasFile('tcs'))
            {
             $image = $request->tcs;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $userData['tcs'] = $filename;
            }
             

            // dd($userData);
            // return $userData;exit();

            $user = User::create($userData);

            // send-mail
            $data = [
                'email'=>$request->email,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
             ];

            // Mail::send(new Register($data));

            $response['success']['message']="User Created";
            return Response::json($response);

        } catch (\Exception $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
        }
       
   }

   public function show($id)
    {
        try{
            $user_detail=User::where('id',$id)->first();
            if($user_detail){
                $response['success']['user_detail'] = $user_detail;
                return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exist";
                return Response::json($response);
            }
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }

    public function destroy($id)
    {
        try{
            $search_user=User::where('id',$id)->first();
            if($search_user){
                User::where('id',$id)->delete();
                $response['success']['message'] ="User Deleted";
                return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exist for delete";
                return Response::json($response);
            }
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }

    public function update(Request $request, $id)
    {
        try{
            $srch_reg=User::where('id',$id)->first();
            $up=array();
            if($srch_reg){
                if($request->name){
                    $up['name']=$request->name;
                }
                if($request->email){
                    $up['email']=$request->email;
                }
                if($request->gstin){
                    $up['gstin']=$request->gstin;
                }
                if($request->org_pan){
                    $up['org_pan']=$request->org_pan;
                }
                if($request->org_name){
                    $up['org_name']=$request->org_name;
                }
                if($request->org_address){
                    $up['org_address']=$request->org_address;
                }
                if($request->pref_product){
                    $up['pref_product']=$request->pref_product;
                }
                if($request->pref_product_size){
                    $up['pref_product_size']=$request->pref_product_size;
                }
                if($request->user_type){
                    $up['user_type']=$request->user_type;
                }
                if($request->company_gst){
                    $up['company_gst']=$request->company_gst;
                }
                if($request->company_linked_address){
                    $up['company_linked_address']=$request->company_linked_address;
                }
                if($request->company_pan){
                    $up['company_pan']=$request->company_pan;
                }
                if($request->company_name){
                    $up['company_name']=$request->company_name;
                }
                if($request->business_nature){
                    $up['business_nature']=$request->business_nature;
                }
                if($request->first_name){
                    $up['first_name']=$request->first_name;
                }
                if($request->last_name){
                    $up['last_name']=$request->last_name;
                }
                if($request->addressone){
                    $up['addressone']=$request->addressone;
                }
                if($request->addresstwo){
                    $up['addresstwo']=$request->addresstwo;
                }
                if($request->city){
                    $up['city']=$request->city;
                }
                if($request->state){
                    $up['state']=$request->state;
                }
                if($request->pincode){
                    $up['pincode']=$request->pincode;
                }
                if($request->address_type){
                    $up['address_type']=$request->address_type;
                }
                
            
            if ($request->hasFile('address_proof_file'))
            {
             $image = $request->address_proof_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['address_proof_file'] = $filename;
            }


            if ($request->hasFile('cancel_cheque_file'))
            {
             $image = $request->cancel_cheque_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['cancel_cheque_file'] = $filename;
            }


            if ($request->hasFile('pan_card_file'))
            {
             $image = $request->pan_card_file;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['pan_card_file'] = $filename;
            }


            if ($request->hasFile('gst_certificate'))
            {
             $image = $request->gst_certificate;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['gst_certificate'] = $filename;
            }


            if ($request->hasFile('turnover_declare'))
            {
             $image = $request->turnover_declare;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['turnover_declare'] = $filename;
            }


            if ($request->hasFile('itr_last_yr'))
            {
             $image = $request->itr_last_yr;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['itr_last_yr'] = $filename;
            }
            
            
            if ($request->hasFile('form_d'))
            {
             $image = $request->form_d;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['form_d'] = $filename;
            }
            
            
            if ($request->hasFile('registration_certificate'))
            {
             $image = $request->registration_certificate;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['registration_certificate'] = $filename;
            }
            
            
            if ($request->hasFile('tcs'))
            {
             $image = $request->tcs;
             $filename = time() . '-' . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
             $image->move("storage/app/public/user",$filename);
             $up['tcs'] = $filename;
            }
             

                    
                    $update=User::where('id',$id)->update($up);
                    $fetch_user=User::where('id',$id)->first();
                    $response['success']['message'] = "User updated";
                    $response['success']['region'] = $fetch_user;
                    return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exists for update";
                return Response::json($response);
            }
           
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }



    public function getUserById($id)
    {
        try{
            $user = array();
            $search_user= User::where('id',$id)->first();
            if($search_user){

                // foreach ($variable as $key => $value) {
                     
                     $user['id'] = $search_user->id;
                     $user['name'] = $search_user->name;
                     $user['email'] = $search_user->email;
                     $user['phone'] = $search_user->phone;
                     $user['email_verified_at'] = $search_user->email_verified_at;
                     $user['gstin'] = $search_user->gstin;
                     $user['org_pan'] = $search_user->org_pan;
                     $user['org_name'] = $search_user->org_name;
                     $user['org_address'] = $search_user->org_address;
                     $user['pref_product'] = $search_user->pref_product;
                     $user['pref_product_size'] = $search_user->pref_product_size;
                     $user['company_gst'] = $search_user->company_gst;
                     $user['company_pan'] = $search_user->company_pan;
                     $user['company_name'] = $search_user->company_name;
                     $user['business_nature'] = $search_user->business_nature;
                     $user['first_name'] = $search_user->first_name;
                     $user['last_name'] = $search_user->last_name;
                     $user['addressone'] = $search_user->addressone;
                     $user['addresstwo'] = $search_user->addresstwo;
                     $user['city'] = $search_user->city;
                     $user['state'] = $search_user->state;
                     $user['pincode'] = $search_user->pincode;
                     $user['address_proof_file'] = asset('storage/app/public/user/'.$search_user->address_proof_file);
                     $user['cancel_cheque_file'] = asset('storage/app/public/user/'.$search_user->cancel_cheque_file);
                     $user['pan_card_file'] = asset('storage/app/public/user/'.$search_user->pan_card_file);
                     $user['gst_certificate'] = asset('storage/app/public/user/'.$search_user->gst_certificate);
                     $user['turnover_declare'] = asset('storage/app/public/user/'.$search_user->turnover_declare);
                     $user['itr_last_yr'] = asset('storage/app/public/user/'.$search_user->itr_last_yr);
                     $user['form_d'] = asset('storage/app/public/user/'.$search_user->form_d);
                     $user['registration_certificate'] = asset('storage/app/public/user/'.$search_user->registration_certificate);
                     $user['tcs'] = asset('storage/app/public/user/'.$search_user->tcs);
                     $user['kam_details'] = $this->kam_details($search_user->id,$search_user->zone);

                // }
                
                return response()->json(['status'=>1,'message' =>'Successful','result' => $user],200);
            }else{
                 $user = [];
                return response()->json(['status'=>1,'message' =>'Not found','result' =>$user],200);
            }
            
           } catch (\Throwable $th) {
            $error = $th->getMessage();
            return response()->json(['status'=>0,'message' =>'error','result' => $error],200);
           } 
    }

    public function kam_details($id,$zone)
    {
         $kam_details = User::where('zone',$zone)->where('id','!=',$id)->first();
         return $kam_details;
    }


    public function registerEmail(Request $request)
    {
        // echo "<pre>";print_r($request->email);exit();

             $email = $request->email;
             $sub = 'You have successfully regsitered';
     
             $html = 'mail.register';

             $cc_email = "";
             $data = "";

            (new MailService)->dotestMail($sub,$html,$email,$data,$cc_email);
             // Mail::send(new RfqGeneratedMail($data));

             $msg = "Mail sent successfully";
             return response()->json(['status'=>1,'message' =>$msg],200);
    }


        /**
     * This is for validate user mobile OTP.
     *
     * @return \Illuminate\Http\Response
    */
    public function chkEmail(Request $request)
    {
       $validator = Validator::make($request->all(), [ 
             
            'email' => ['required', 'string','max:255','regex:/^\w+[-\.\w]*@(?!(?:myemail)\.com$)\w+[-\.\w]*?\.\w{2,4}$/'],              
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['status'=>0,'message' =>'error','result' => $validator->errors()],200); 
        }

        $chkemail = User::where('email',$request->email)->first();
         
        if (empty($chkemail)) 
        {
            
              return response()->json(['status'=>1,'message' =>'success']);

        }
        else
        {
            return response()->json(['status'=>0,'message' =>'success','result'=>'It looks like you already signed up, login to your account.'],200);
            // return response()->json(['status'=>0,'message' => [],'status' => 200]);
        }

        
         

    }
}