<?php

namespace App\Http\Controllers\Api\Modules\Requote;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RequoteCount;

class RequoteController extends Controller
{
	    public function updateCountRequote(Request $request)
	    {

	    	  try{ 
                     $count = RequoteCount::where('rfq_no',$request->input('rfq_no'))->get()->toArray();
                     // echo "<pre>";print_r($count);exit();
	                 if(!empty($count))
	                 {
	                 	 $countRfq = RequoteCount::where('rfq_no',$request->input('rfq_no'))->first()->toArray();

	                 	 $newcount = $countRfq['counts'] + $request->input('counts');
	                 	 // echo "<pre>";print_r($newcount);exit();

	                 	 RequoteCount::where('rfq_no',$request->input('rfq_no'))->update(['counts' => $newcount]);


	                 }else{

	                 	 $data['rfq_no'] = $request->input('rfq_no');
	                 	 $data['counts'] = $request->input('counts');
	                 	 $data['status'] = 1;

	                 	 RequoteCount::create($data);
	                 }


			    	 
			        return response()->json(['status'=>1,
			          'message' =>'success',
			          'result' => 'Requote count updated'],
			          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }

	    	 
	    }


  // ----------------------------- get requote counts --------------------------------------------

	   public function getCountRequote($rfq_no)
	    {

	    	  try{ 
                   
                 	 $countRfq = RequoteCount::where('rfq_no',$rfq_no)->first();
                     
                     if(!empty($countRfq))
                     {

                 	   $newcount = $countRfq->counts;
                     }
                     else{

                     	$newcount = 0;
                     }
                 	 // echo "<pre>";print_r($newcount);exit();
			    	 
			        return response()->json(['status'=>1,
			          'message' =>'success',
			          'result' => $newcount],
			          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }

	    	 
	    }

	 // ---------------------------------------------------------------------------
}
