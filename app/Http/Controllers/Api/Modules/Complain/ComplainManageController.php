<?php

namespace App\Http\Controllers\Api\Modules\Complain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ComplaintMail;
use App\Mail\ComplaintMailToRm;
use App\User;
use App\Models\Department;
use App\Models\DepartmentMail;
use App\Models\ComplaintManage;
use App\Models\ComplainRemarks;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;


class ComplainManageController extends Controller
{
    /**
     * This is for add get Complain Details. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function sendComMail(Request $request)
    {

      try{
            $validator = Validator::make($request->all(), [              
            'r_mail'        => 'required|email',
            'depa_id'        => 'required', 
            'po_no'        => 'required',
            'kam_id'        => 'required', 
            'ka_remarks'        => 'required',
            ]);

            if ($validator->fails()) { 
                return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
            }

            $input['depa_id'] = $request->depa_id;
            $input['r_mail'] = $request->r_mail;
          	$input['ka_remarks'] = $request->ka_remarks;
          	$input['kam_id'] = $request->kam_id;
            $input['po_no'] = $request->po_no;
          	 
            $freightsData = ComplaintManage::create($input);
          	
          	return response()->json(['status'=>1,'message' =>'Complaint mail send to successfully.'],200);

            // $remarksData = ComplainRemarks::where('complain_id',$ComplainListData->id)->get();

          // This is for get RM mail address....
          // if (isset($request->kam_id)) {
          //   $getkam = User::where('id',$request->kam_id)->first(); 
          // }
           
          // $mailData['email'] = $request->r_mail;
          // $mailData['customer_name'] = $ComplainListData->customer_name;
          // $mailData['com_cate_name'] =  $ComplainListData->com_cate_name;
          // $mailData['com_sub_cate_name'] = $ComplainListData->com_sub_cate_name;          
          // $mailData['po_no'] = $ComplainListData->po_no;
          // $mailData['kam_name'] = $getkam->name;
          // $mailData['remarksData'] = $remarksData;


          // Mail::send(new ComplaintMailToRm($data));
         // if (!empty($mailData)){
         //     // Mail::send(new ComplaintMailToRm($mailData));
         //    return response()->json(['status'=>1,'message' =>'Complaint mail send to successfully.'],200);
         //  }
         //  else{

         //   return response()->json(['status'=>1,'message' =>'No data found','result' => []],config('global.success_status'));

         //  } 
           
            
          }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json([$response]);
          }
    }
}
