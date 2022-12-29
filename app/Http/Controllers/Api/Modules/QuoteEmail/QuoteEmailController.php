<?php

namespace App\Http\Controllers\Api\Modules\QuoteEmail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Plant;
use App\Models\DeliveryMethod;
// use App\Mail\RfqGeneratedMail;
// use App\Mail\AcceptedRfqMail;
// use App\Mail\OrderConfirmationMail;
// use App\Mail\SalesacceptMail;
use App\ServicesMy\MailService;
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

         $sub = 'Your RFQ has been raised successfully'.'   '.$rfq_no;
 
         $html = 'mail.rfqgeneratedmail';

         $data = "";

    	 // $data['name'] = $user['name'];
      //    $data['email'] = $user['email'];
      //    $data['rfq_no'] = $rfq_no;
      //    $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();
        (new MailService)->dotestMail($sub,$html,$user['email'],$data,$cc_email);
         // Mail::send(new RfqGeneratedMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }


    // --------------------  accepted price mail ------------------------------------
    public function acceptedPriceMail(Request $request)
    {
         $cc_email = array();

         $rfq_no = $request->input('rfq_no');
         $user_id = $request->input('user_id');
         $kam_id = $request->input('kam_id');
         
         
         $user = User::where('id',$user_id)->first();

         $cam = User::where('zone',$user->zone)->where('id','!=',$user_id)->where('id','!=',$kam_id)->where('user_type','Kam')->get()->toArray();

         foreach ($cam as $key => $value) {
             
              array_push($cc_email,$value['email']);
         }

         

         $data['name'] = $user['name'];
         $data['email'] = $user['email'];
         $data['rfq_no'] = $rfq_no;
         $data['cc'] = $cc_email;
         // echo "<pre>";print_r($data);exit();

         Mail::send(new AcceptedRfqMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // -------------------------------------------------------------------------------

    // --------------------  order confirmation mail --------------------------------
    public function orderCnrfmMail(Request $request)
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

         Mail::send(new OrderConfirmationMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // ------------------------------------------------------------------------------

    // --------------------  sales acceptance mail --------------------------------
    public function saleAccptMail(Request $request)
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

         Mail::send(new SalesacceptMail($data));

         $msg = "Mail sent successfully";
         return response()->json(['status'=>1,'message' =>$msg],200);
    }
    // -----------------------------------------------------------------------------


}
