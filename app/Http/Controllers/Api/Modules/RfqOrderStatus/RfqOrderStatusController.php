<?php

namespace App\Http\Controllers\Api\Modules\RfqOrderStatus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\RfqOrderStatusCust;
use App\Models\RfqOrderStatusKam;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 
use Mail;

class RfqOrderStatusController extends Controller
{
    /**
     * This is for Store Rfq Order Status Kam. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function StoreRfqOrderStatusKam(Request $request)
    {

      \DB::beginTransaction();

        try{

          // $validator = Validator::make($request->all(), [ 
          //   'rfq_no'        => 'required', 
          // ]);

          // if ($validator->fails()) { 
          //     return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          // }

         
          
          $input['rfq_no'] = $request->rfq_no;
          $input['rfq_submited'] = $request->rfq_submited;
          $input['quoted_by_tsml'] = $request->quoted_by_tsml;
          $input['under_negotiation'] = $request->under_negotiation;
          $input['final_quoted_by_tsml'] = $request->final_quoted_by_tsml;
          $input['rfq_closed'] = $request->rfq_closed;
           
          // dd($input);
          $chk = RfqOrderStatusKam::where('rfq_no',$request->rfq_no)->first();
           
          if (!empty($chk)) {

          	if ($request->rfq_submited) {
          		$inputa['rfq_submited'] = $request->rfq_submited;          		
          		$freightsData = RfqOrderStatusKam::where('user_id',$request->user_id)->where('rfq_no',$request->rfq_no)->update($inputa);
          	}
          	if ($request->quoted_by_tsml) {

          		$inputb['quoted_by_tsml'] = $request->quoted_by_tsml;          		
          		$freightsData = RfqOrderStatusKam::where('user_id',$request->user_id)->where('rfq_no',$request->rfq_no)->update($inputb);
          	}
          	if ($request->under_negotiation) {
          		$inputc['under_negotiation'] = $request->under_negotiation;          		
          		$freightsData = RfqOrderStatusKam::where('user_id',$request->user_id)->where('rfq_no',$request->rfq_no)->update($inputc);
          	}
          	if ($request->final_quoted_by_tsml) {
          		$inputd['final_quoted_by_tsml'] = $request->final_quoted_by_tsml;          		
          		$freightsData = RfqOrderStatusKam::where('user_id',$request->user_id)->where('rfq_no',$request->rfq_no)->update($inputd);
          	}
          	if ($request->rfq_closed) {
          		$inpute['rfq_closed'] = $request->rfq_closed;          		
          		$freightsData = RfqOrderStatusKam::where('user_id',$request->user_id)->where('rfq_no',$request->rfq_no)->update($inpute);
          	} 
          	
          }else{
          	$freightsData = RfqOrderStatusKam::create($input);
          }
          

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Success'],config('global.success_status'));
          }
          else
          { 
            return response()->json(['status'=>1,'message' =>'Somthing went wrong','result' => []],config('global.success_status'));
          } 
           

        }catch(\Exception $e){ 
          \DB::rollback(); 
          return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
    }

    /**
     * This is for get get Rfq Orde rStatus Kam. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getRfqOrderStatusKam(Request $request)
    {
      try{ 
            $statusData = RfqOrderStatusKam::where('rfq_no',$request->rfq_no)->first();  
             
            if (!empty($statusData)) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $statusData],200);
            }
            else{

               return response()->json(['status'=>1,'message' =>'No data found','result' => []],config('global.success_status'));

            }
            
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            }


    }

    /**
     * This is for Store Rfq Order Status Kam. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function StoreRfqOrderStatusCust(Request $request)
    {

      \DB::beginTransaction();

        try{

          // $validator = Validator::make($request->all(), [ 
          //   'rfq_no'        => 'required', 
          // ]);

          // if ($validator->fails()) { 
          //     return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          // }

         
          
          $input['rfq_no'] = $request->rfq_no;
          $input['rfq_submited'] = $request->rfq_submited;
          $input['quoted_by_tsml'] = $request->quoted_by_tsml;
          $input['under_negotiation'] = $request->under_negotiation;
          $input['final_quoted_by_tsml'] = $request->final_quoted_by_tsml;
          $input['rfq_closed'] = $request->rfq_closed;
          
          // dd($input);
          $chk = RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->first();
            
          if (!empty($chk)) {

          	if ($request->rfq_submited) {
          		$inputa['rfq_submited'] = $request->rfq_submited;          		
          		$custData = RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->update($inputa);
          	}
          	if ($request->quoted_by_tsml) {
          		// dd('ok');
          		$inputb['quoted_by_tsml'] = $request->quoted_by_tsml;          		
          		RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->update($inputb);
          	}
          	if ($request->under_negotiation) {
          		$inputc['under_negotiation'] = $request->under_negotiation;          		
          		$custData = RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->update($inputc);
          	}
          	if ($request->final_quoted_by_tsml) {
          		$inputd['final_quoted_by_tsml'] = $request->final_quoted_by_tsml;          		
          		$custData = RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->update($inputd);
          	}
          	if ($request->rfq_closed) {
          		$inpute['rfq_closed'] = $request->rfq_closed;          		
          		$custData =RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->update($inpute);
          	} 
          	
          	
          }else{
          	$custData = RfqOrderStatusCust::create($input);
          }
          

          \DB::commit();

           
        return response()->json(['status'=>1,'message' =>'Success'],config('global.success_status'));
           
           

        }catch(\Exception $e){ 
          \DB::rollback(); 
          return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
    }

    /**
     * This is for get get Rfq Orde rStatus Kam. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getRfqOrderStatuCust(Request $request)
    {
      try{ 
            $statusData = RfqOrderStatusCust::where('rfq_no',$request->rfq_no)->first();  
             
            if (!empty($statusData)) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $statusData],200);
            }
            else{

               return response()->json(['status'=>1,'message' =>'No data found','result' => []],config('global.success_status'));

            }
            
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            }


    }
}
