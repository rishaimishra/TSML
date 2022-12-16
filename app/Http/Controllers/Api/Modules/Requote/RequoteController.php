<?php

namespace App\Http\Controllers\Api\Modules\Requote;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RequoteCount;
use App\Models\ScTransaction;
use App\Models\Smremark;
use DB;
use Validator;

class RequoteController extends Controller
{
	    public function updateCountRequote(Request $request)
	    {

	    	  try{ 
                   // echo "<pre>";print_r($request->all());exit();
	    	  	   foreach ($request->all() as $key => $value) {
	    	  	   
                     $count = RequoteCount::where('sche_no',$value['sche_no'])->get()->toArray();
                     // echo "<pre>";print_r($count);exit();
	                 if(!empty($count))
	                 {
	                 	 $countRfq = RequoteCount::where('sche_no',$value['sche_no'])->first()->toArray();

	                 	 $newcount = $countRfq['counts'] + $value['counts'];
	                 	 // echo "<pre>";print_r($newcount);exit();

	                 	 RequoteCount::where('sche_no',$value['sche_no'])->update(['counts' => $newcount]);


	                 }else{

	                 	 $data['sche_no'] = $value['sche_no'];
	                 	 $data['counts'] = $value['counts'];
	                 	 $data['status'] = 1;

	                 	 RequoteCount::create($data);
	                 }
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
                   
                 	 $countRfq = RequoteCount::where('sche_no',$rfq_no)->first();
                     
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


   /*
      ----------------  quote schedule list -------------------

  */

      public function getCountSche($id)
      {

       \DB::beginTransaction();

       try{ 

          $result = 0;
          $quoteArr = array();

          $quote_ids = DB::table('quotes')->where('rfq_no',$id)->whereNull('deleted_at')
          ->select('id','user_id')->get();
          // echo "<pre>";print_r($quote_ids);exit();
          foreach ($quote_ids as $key => $value) {
           
           array_push($quoteArr,$value->id);
 
         }
         

         $quote = DB::table('quote_schedules')->whereIn('quote_id',$quoteArr)->get();
   
         // echo "<pre>";print_r($quote);exit();

         foreach ($quote as $key => $value) {
           
           if($value->quote_status != 3)
           {

             $result ++;
           }
          
        }

         $data['total'] = count($quote);
         $data['aac_rej'] = $result;
              // echo "<pre>";print_r($result);exit();
        \DB::commit();

          return response()->json(['status'=>1,'message' =>'success','result' => $data],config('global.success_status'));


   }catch(\Exception $e){

     \DB::rollback();

     return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
   }
 }



      public function priceBreakSave(Request $request)
      {

          try{ 

              $data = array();
                   // echo "<pre>";print_r($request->all());exit();
               foreach ($request->all() as $key => $value) {
                  
                  // echo "<pre>";print_r($value['schedule']);exit();
                  $res = ScTransaction::where('rfq_no',$value['rfq_no'])->where('schedule',$value['schedule'])->get();
                  if(!empty($res))
                  {
                      ScTransaction::where('rfq_no',$value['rfq_no'])->where('schedule',$value['schedule'])->delete();
                  }
                  $mat_code = DB::table('product_size_mat_no')
                  ->where('sub_cat_id',$value['sub_cat_id'])->where('product_size',$value['size'])->first();
                   
                    foreach ($value['components'] as $k => $v) {
                        
                          $data['code'] = $v['comp'];
                          $data['value'] = $v['value'];
                          $data['mat_code'] = $mat_code->mat_no;
                          $data['plant'] = $value['plant'];
                          $data['schedule'] = $value['schedule'];
                          $data['rfq_no'] = $value['rfq_no'];

                          ScTransaction::create($data);

                    }



               }


             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => 'Requote count updated'],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }


      // ----------------------------- get requote counts -------------------------

     public function getPriceComp()
      {

          try{ 
                   
               $res = DB::table('price_masters')->get();

                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $res],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------


 // ----------------------------- get price break -------------------------

     public function getPriceBreak(Request $request)
      {

          try{ 
                   
               $rfq_no = $request->input('rfq_no');
               $sche_no = $request->input('sche_no');
               
               $res = ScTransaction::where('rfq_no',$rfq_no)->where('schedule',$sche_no)
               ->select('code','value')->get();
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $res],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------
   

    // ----------------------------- sm remark save -------------------------

     public function smRemarkSave(Request $request)
      {

          try{ 
               
               $validation = \Validator::make($request->all(),[ 
                    "remarks" => "required",
                    
              ],[ 
                'remarks.required'=>'Remarks is required.',
                    
              ]);

               if ($validation->fails()) {
                  return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
              }


               $user_id = $request->input('user_id');
               $rfq_no = $request->input('rfq_no');
               $remarks = $request->input('remarks');
               
               $res = Smremark::where('rfq_no',$rfq_no)->get();
               if(!empty($res))
               {
                   Smremark::where('rfq_no',$rfq_no)->delete();
               }

               $arr['user_id'] = $user_id;
               $arr['rfq_no'] = $rfq_no;
               $arr['remarks'] = $remarks;


                   // echo "<pre>";print_r($arr);exit();
               Smremark::create($arr);
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => 'Remarks updated'],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------


}
