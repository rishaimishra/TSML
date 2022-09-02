<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Response;
use App\User;
use Mail;
use App\Mail\Register;
use Tymon\JWTAuth\Exceptions\JWTException;


 
class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $users = User::all();
 
       return $users;
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
 

                $validator = Validator::make($request->all(), [ 
                    'current_password' =>'required|string|min:6',
                    'password' =>'required|string|min:6|required_with:password-confirm', 
                    'password_confirm' =>'required|required_with:password|same:password',   
                ],
                [   
                    'current_password.required'=>'The current password field is required',
                    'password_confirm.same'=>'The confirm password and password must match.',
                    'password_confirm.required'=>'The confirm password field is required'
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
                'name'        => 'required', 
                'email'        => 'required',   
                'password'        => 'required',  
                'address_proof_file' => 'required|mimes:jpg,jpeg,png,bmp' 
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
                if($request->address_proof_file){
                    $up['address_proof_file']=$request->address_proof_file;
                }
                if($request->cancel_cheque_file){
                    $up['cancel_cheque_file']=$request->cancel_cheque_file;
                }
                if($request->pan_card_file){
                    $up['pan_card_file']=$request->pan_card_file;
                }
                if($request->gst_certificate){
                    $up['gst_certificate']=$request->gst_certificate;
                }
                if($request->turnover_declare){
                    $up['turnover_declare']=$request->turnover_declare;
                }
                if($request->itr_last_yr){
                    $up['itr_last_yr']=$request->itr_last_yr;
                }
                if($request->form_d){
                    $up['form_d']=$request->form_d;
                }
                if($request->registration_certificate){
                    $up['registration_certificate']=$request->registration_certificate;
                }
                if($request->tcs){
                    $up['tcs']=$request->tcs;
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
}