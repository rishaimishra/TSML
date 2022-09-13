<?php

namespace App\Http\Controllers\Api\Modules\Quote;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteSchedule;
use Auth;
use DB;

class QuoteController extends Controller
{

	/*
      ---------------- first time quote submission -------------------

	*/
    public function storeQuotes(Request $request)
    {

       try{ 

	    	$quoteArr = array();

	    	$user_id = Auth::user()->id;
	        
	        $rfq_number = (!empty($request->input('rfq_number'))) ? $request->input('rfq_number') : '';

	    	$quotes = $this->configureQuotes($request,$rfq_number,$user_id);
	        // echo "<pre>";print_r($quotes);exit();
	        if(!empty($quotes))
	        {
	        	return response()->json(['status'=>1,
		        		'message' =>'success',
		        		'result' => $quotes],
		        		config('global.success_status'));
	        }
	        else{

	           return response()->json(['status'=>1,
		           	'message' =>'success',
		           	'result' => 'Quote not created'],
		           	config('global.success_status'));

	        }

      }catch(\Exception $e){

           return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
      }
    }

    
    /*
      ----------------  quote update -------------------

	*/

    public function updateQuotes(Request $request)
    {

       try{ 

           $chk_quote = Quote::where('rfq_no',$request->input('rfq_number'))->count();
           // echo "<pre>";print_r($chk_quote);exit();
           if($chk_quote > 0)
           {
	    	$quoteArr = array();

	        
	        $rfq_number = (!empty($request->input('rfq_number'))) ? $request->input('rfq_number') : '';

	        $quote_id = DB::table('quotes')->where('rfq_no',$rfq_number)->whereNull('deleted_at')->select('id','user_id')->first();
	        // echo "<pre>";print_r($quote_id->user_id);exit();
	        Quote::where('rfq_no',$rfq_number)->delete();
	        QuoteSchedule::where('quote_id',$quote_id->id)->delete();

	    	$quotes = $this->configureQuotes($request,$rfq_number,$quote_id->user_id);
	        // echo "<pre>";print_r($quotes);exit();
	        if(!empty($quotes))
	        {
	        	return response()->json(['status'=>1,'message' =>'success','result' => $quotes],config('global.success_status'));
	        }
	        else{

	           return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

	        }
	    }
	    else{

	           return response()->json(['status'=>1,'message' =>'Quote do no exists','result' => []],config('global.success_status'));

	        }


      }catch(\Exception $e){

           return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
      }
    }


    /*
      ---------------- quote configure -------------------

	*/
    public function configureQuotes($request,$rfq_number=NULL,$user_id)
    {
    	$rfq_num = (!empty($rfq_number)) ? $rfq_number : rand(100,9999);

        $quoteArr['user_id']    = $user_id;
        $quoteArr['product_id'] = $request->input('product_id');
        $quoteArr['quantity'] = $request->input('quantity');
        $quoteArr['kam_price'] = $request->input('kam_price');
        $quoteArr['expected_price'] = $request->input('expected_price');
        $quoteArr['plant'] = $request->input('plant');
        $quoteArr['location'] = $request->input('location');
        $quoteArr['rfq_no'] = $rfq_num;

        $schedules = $request->input('quote_schedules');

        $quote = Quote::create($quoteArr);

        foreach ($schedules as $key => $value) {
        	
        	$sche['quote_id'] = $quote['id'];
        	$sche['quantity'] = $value['quantity'];
        	$sche['to_date'] = $value['to_date'];
        	$sche['from_date'] = $value['from_date'];
        	// echo "<pre>";print_r($sche);exit();

            QuoteSchedule::create($sche);

        }

        if($quote)
        {
        	 return $quote;
        }
        else{

        	return [];
        }
    	// echo "<pre>";print_r($schedules);exit();
    }


    /*
      ---------------- quote status update  -------------------

	*/
      public function quotesStatusUpdate(Request $request)
      {

	      	try{ 
	              
	               $quote_id = $request->input('quote_id');
	      	       $status = $request->input('status');

		          $updated = Quote::where('id',$quote_id)->update(['kam_status' => $status]);
		          if($updated)
		          {
		          	   return response()->json(['status'=>1,
				           	'message' =>'status updated',
				           	'result' => $updated],
				           	config('global.success_status'));
		          }
		      	  // echo "<pre>";print_r($quotes);exit();

	          }catch(\Exception $e){

			           return response()->json(['status'=>0,
				           	'message' =>'error',
				           	'result' => $e->getMessage()],
				           	config('global.failed_status'));
	         }
      	  
      }
}
