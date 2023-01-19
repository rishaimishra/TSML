<?php

namespace App\Http\Controllers\Api\Modules\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
  use Illuminate\Support\Facades\Auth;
  use App\User;
  use App\Models\SecurityQuestion; 
  use JWTAuth;
  use Validator;

class SecurityQuestionController extends Controller
{
    /**
         * This is for store new Security Question.
         *
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
         */
   	public function StoreSecurityQue(Request $request)
   	{ 
   		 
   		\DB::beginTransaction();

   		try{

   			$validator = Validator::make($request->all(), [
                's_question'        => 'required', 
	        ]);

	        if ($validator->fails()) { 
	            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
	        }

	        $input['s_question'] = $request->s_question;
    	   	$input['status'] = 0; 

    	   	$freightsData = SecurityQuestion::create($input);

    	   	\DB::commit();

    	   	if($freightsData)
                {
		            return response()->json(['status'=>1,'message' =>'Security question added successfully','result' => $freightsData],config('global.success_status'));
		        }
		        else{ 
		         	return response()->json(['status'=>1,'message' =>'Somthing went wrong','result' => []],config('global.success_status'));
		        } 
    		 

   		}catch(\Exception $e){ 
    	  	\DB::rollback(); 
           	return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
        }
    }

    /**
     * This is for show Security Question . 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getSecurityQue(Request $request)
    {
        
        try{         
        $data = SecurityQuestion::where('status','!=',1)->orderBy('id','desc')->get();

        $squadata = [];
        foreach ($data as $key => $value) 
        {

        	$sqdata['security_question_id'] = $value->id;
        	$sqdata['s_question'] = $value->s_question; 
          
        	$squadata[] = $sqdata;
        } 
          
        return response()->json(['status'=>1,'message' =>'success.','result' => $squadata],200);
          
          
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return response()->json([$response]);
        }
    }
}
