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


     /*
      ---------------- quote history customer  -------------------

	*/
     public function quotesHistoryCustomer($rfq_no)
      {  

      	  \DB::beginTransaction();

      	  try{ 


      	  	    $user_id = Auth::user()->id;


                 $res = $this->getQuoteHistory($user_id,$rfq_no);
                  

		         
                 
                 // echo "<pre>";print_r($res);exit();


                 \DB::commit();

                 if(!empty($res))
                 {
		             return response()->json(['status'=>1,
		        		'message' =>config('global.sucess_msg'),
		        		'result' => $res],
		        		config('global.success_status'));
		         }
		         else{

		         	 return response()->json(['status'=>1,
		        		'message' =>'not found',
		        		'result' => []],
		        		config('global.success_status'));
		         }


            }catch(\Exception $e){

            	   \DB::rollback();

                   return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
          }

         
      }



      public function getQuoteHistory($user_id=NULL,$rfq_no=NULL)
      {

      	   $quoteArr=array();

           $res = DB::table('quotes')
              ->leftjoin('users','quotes.user_id','users.id')
              ->leftjoin('products','quotes.product_id','products.id')
              ->select('quotes.*','users.name','products.pro_name','products.pro_desc')
              ->whereNotNull('quotes.deleted_at');
              

           if(!empty($user_id))
           {
           	   $res = $res->where('quotes.user_id',$user_id);
           }
           if(!empty($rfq_no))
           {
           	   $res = $res->where('quotes.rfq_no',$rfq_no);
           }

           $res = $res->get();

           foreach ($res as $key => $value) {
           	   
           	    $quoteArr[$key]['name'] = $value->name;
           	    $quoteArr[$key]['pro_name'] = $value->pro_name;
           	    $quoteArr[$key]['pro_desc'] = $value->pro_desc;
           	    $quoteArr[$key]['rfq_no'] = $value->rfq_no;
           	    $quoteArr[$key]['quantity'] = $value->quantity;
           	    $quoteArr[$key]['kam_price'] = $value->kam_price;
           	    $quoteArr[$key]['expected_price'] = $value->expected_price;
           	    $quoteArr[$key]['plant'] = $value->plant;
           	    $quoteArr[$key]['location'] = $value->location;
           	    $quoteArr[$key]['schedules'] = $this->quoteSchedule($value->id);
           	  

           }

           return $quoteArr;
      }


      public function quoteSchedule($quote_id)
      {
             $schedules = DB::table('quote_schedules')->where('quote_id',$quote_id)
           	       ->select('quantity','to_date','from_date')->whereNotNull('deleted_at')->get();

           	     foreach ($schedules as $key => $value) {
           	     	 
           	     	  $schArr[$key]['quantity'] = $value->quantity;
           	     	  $schArr[$key]['to_date'] = $value->to_date;
           	     	  $schArr[$key]['from_date'] = $value->from_date;
           	     }

           	  return $schArr;
      }


      /*
      ---------------- quote list  -------------------

	*/
     public function getQuotesList()
      {  

      	  \DB::beginTransaction();

      	  try{ 


      	  	    $user_id = Auth::user()->id;


                 // $res = $this->getQuoteHistory($user_id,$rfq_no);
                  

		         $quotes = Quote::where('user_id',$user_id)->with('schedules')->orderBy('updated_at','desc')->get()->toArray();
                
                 // echo "<pre>";print_r($quotes);exit();


                 \DB::commit();

                 if(!empty($quotes))
                 {
		             return response()->json(['status'=>1,
		        		'message' =>config('global.sucess_msg'),
		        		'result' => $quotes],
		        		config('global.success_status'));
		         }
		         else{

		         	 return response()->json(['status'=>1,
		        		'message' =>'not found',
		        		'result' => []],
		        		config('global.success_status'));
		         }


            }catch(\Exception $e){

            	   \DB::rollback();

                   return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
          }

         
      }
}
