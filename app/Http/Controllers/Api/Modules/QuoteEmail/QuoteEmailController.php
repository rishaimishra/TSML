<?php

namespace App\Http\Controllers\Api\Modules\QuoteEmail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteSchedule;
use App\Models\ProductSubCategory;
use App\Models\Order;
use App\Models\Deleteremark;
use App\Models\Plant;
use App\Models\DeliveryMethod;
use App\Mail\RfqGeneratedMail;
use App\User;
use Validator;
use Auth;
use DB;
use \PDF;
use Mail;

class QuoteEmailController extends Controller
{
    public function quotePoMail(Request $request)
    {
         $cc_email = array();

    	 $rfq_no = $request->input('rfq_no');
    	 $user_id = $request->input('user_id');
    	 
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
         	 
         	  array_push($cc_email,$value['email']);
         }

         

    	 $data['name'] = $user['name'];
         $data['email'] = $user['email'];
         $data['rfq_no'] = $rfq_no;
         $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();

         Mail::send(new RfqGeneratedMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }


    // --------------------  accepted price mail ----------------------------------------------
    public function acceptedPriceMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         

         $data['name'] = $user['name'];
         $data['email'] = $user['email'];
         $data['rfq_no'] = $rfq_no;
         $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();

         Mail::send(new RfqGeneratedMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // --------------------------------------------------------------------------------------------
}
