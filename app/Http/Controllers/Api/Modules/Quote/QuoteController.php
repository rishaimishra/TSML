<?php

namespace App\Http\Controllers\Api\Modules\Quote;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteSchedule;
use App\Models\ProductSubCategory;
use App\Models\Quotedelivery;
use App\Models\Requote;
use App\Models\Order;
use Validator;
use Auth;
use DB;

class QuoteController extends Controller
{

  /*
      ---------------- first time quote submission -------------------

  */
      public function storeQuotes(Request $request)
      {
       // echo "<pre>";print_r($request->all());exit();

       try{ 

        $quoteArr = array();

        $user_id = Auth::user()->id;
        
        // $rfq_number = (!empty($request->input('rfq_number'))) ? $request->input('rfq_number') : '';
        $quote_id = "";
        foreach ($request->all() as $key => $value) {
          
          $array['product_id'] = $value['product_id'];
          $array['cat_id'] = $value['cat_id'];
          $array['quantity'] = $value['quantity'];
          $array['quote_schedules'] = $value['quote_schedules'];
          $rfq_number = $value['rfq_number'];
          
          if(!empty($value['quantity']) ){
            $request = new Request($array);
              // echo "<pre>";print_r($request);exit();
            $quotes = $this->configureQuotes($request,$rfq_number,$user_id,$quote_id);
          }

        }



        
          // echo "<pre>";print_r($quotes);exit();
          // if(!empty($quotes))
          // {
        return response()->json(['status'=>1,
          'message' =>'success',
          'result' => 'Quote created'],
          config('global.success_status'));
          // }
          // else{

          //    return response()->json(['status'=>1,
           //       'message' =>'success',
           //       'result' => 'Quote not created'],
           //       config('global.success_status'));

          // }

      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }

   
    /*
      ----------------  quote update -------------------

  */

      public function updateQuotes(Request $request)
      {
         // echo "<pre>";print_r($request->all());exit();
       try{ 

         
        foreach ($request->all() as $key => $value) {

          $quote_id = DB::table('quotes')->where('rfq_no',$value['rfq_number'])->where('product_id',$value['product_id'])->where('cat_id',$value['cat_id'])->whereNull('deleted_at')->select('id','user_id')->first();
            // echo "<pre>";print_r($quote_id);exit();

          $array['product_id'] = $value['product_id'];
          $array['cat_id'] = $value['cat_id'];
          $array['quantity'] = $value['quantity'];
          $array['quote_schedules'] = $value['quote_schedules'];
          $rfq_number = $value['rfq_number'];
          
              // echo "<pre>";print_r($array);exit();
          $request = new Request($array);


          if($quote_id)
          {
            Quote::where('rfq_no',$rfq_number)->where('cat_id',$value['cat_id'])->delete();
            

            $quotes = $this->configureQuotes($request,$rfq_number,$quote_id->user_id,$quote_id->id);
          }
          else{
                 // echo "hi";exit();
           $quote_id = DB::table('quotes')->where('rfq_no',$rfq_number)->whereNull('deleted_at')->select('id','user_id')->first();
           $quoteId = "";
                 // echo "<pre>";print_r($quote_id->user_id);exit();
           $quotes = $this->configureQuotes($request,$rfq_number,$quote_id->user_id,$quoteId);
         }
       }
          // echo "<pre>";print_r($quotes);exit();
       
       return response()->json(['status'=>1,'message' =>'success','result' => 'Quote updated'],config('global.success_status'));
       
       


     }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }


    /*
      ---------------- quote configure -------------------

  */
      public function configureQuotes($request,$rfq_number=NULL,$user_id,$preQouteId=NULL)
      {
      // $rfq_num = (!empty($rfq_number)) ? $rfq_number : rand(100,9999);
       if(!empty($request->input('quantity')))
       {
        $quoteArr['user_id']    = $user_id;
        $quoteArr['product_id'] = $request->input('product_id');
        $quoteArr['cat_id'] = $request->input('cat_id');
        $quoteArr['quantity'] = $request->input('quantity');
        $quoteArr['rfq_no'] = $rfq_number;
        $quoteArr['quote_no']  = rand(100,9999);

        $schedules = $request->input('quote_schedules');
         // echo "<pre>";print_r($schedules);exit();
        $quote = Quote::create($quoteArr);
        
        foreach ($schedules as $key => $value) {
          
          if(!empty($value['quantity']) &&  !empty($value['expected_price']))
          {
           if(!empty($preQouteId))
           {

            QuoteSchedule::where('quote_id',$preQouteId)->delete();
          }
          $sche['quote_id'] = $quote['id'];
          $sche['quantity'] = $value['quantity'];
          $sche['to_date'] = $value['to_date'];
          $sche['from_date'] = $value['from_date'];
          $sche['pro_size'] = $value['pro_size'];
          $sche['kam_price'] = $value['kam_price'];
          $sche['expected_price'] = $value['expected_price'];
          $sche['plant'] = $value['plant'];
          $sche['location'] = $value['location'];
          $sche['bill_to'] = $value['bill_to'];
          $sche['ship_to'] = $value['ship_to'];
          $sche['delivery'] = $value['delivery'];
          $sche['remarks'] = $value['remarks'];
          $sche['valid_till'] = $value['valid_till'];
          $sche['schedule_no'] = $value['schedule_no'];
          $sche['kamsRemarks'] = $value['kamsRemarks'];
          
          // echo "<pre>";print_r($sche);exit();

          QuoteSchedule::create($sche);
        }

      }

        // if($quote)
        // {
        //   return $quote;
        // }
        // else{

        //  return [];
        // }
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

         if(Auth::check())
         {

          $user_id = Auth::user()->id;
        }


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
     ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
     ->leftjoin('users','quotes.user_id','users.id')
     ->leftjoin('products','quotes.product_id','products.id')
     ->leftjoin('categorys','quotes.cat_id','categorys.id')
     ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
     ->select('quotes.rfq_no','users.name','products.pro_name','products.pro_desc','quote_schedules.*','categorys.cat_name','sub_categorys.sub_cat_name')
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
           // echo "<pre>";print_r($res);exit();

     foreach ($res as $key => $value) {
       
      $quoteArr[$key]['name'] = $value->name;
      $quoteArr[$key]['pro_name'] = $value->pro_name;
      $quoteArr[$key]['pro_desc'] = $value->pro_desc;
      $quoteArr[$key]['size'] = $value->pro_size;
      $quoteArr[$key]['rfq_no'] = $value->rfq_no;
      $quoteArr[$key]['quantity'] = $value->quantity;
      $quoteArr[$key]['kam_price'] = $value->kam_price;
      $quoteArr[$key]['expected_price'] = $value->expected_price;
      $quoteArr[$key]['rfq_date'] = date("d-m-Y", strtotime($value->created_at));  ;
      $quoteArr[$key]['valid_till'] = $value->valid_till;
      $quoteArr[$key]['cat_name'] = $value->cat_name;
      $quoteArr[$key]['sub_cat_name'] = $value->sub_cat_name;
      $quoteArr[$key]['remarks'] = $value->remarks;
      
      

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


                // $user_id = Auth::user()->id;

         if(Auth::check())
         {
           $user_id =  Auth::user()->id;;
           
         }


                 // $res = $this->getQuoteHistory($user_id,$rfq_no);
         $quoteArr = array();    

             // $quotes = Quote::where('user_id',$user_id)->with('schedules')
           //   ->with('product')->orderBy('updated_at','desc')

         $quotes = DB::table('quotes')->leftjoin('users','quotes.user_id','users.id')
         ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
         ->leftjoin('products','quotes.product_id','products.id')
         ->leftjoin('categorys','quotes.cat_id','categorys.id')
         ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
         ->select('quotes.*','users.name',DB::raw('SUM(quotes.quantity) as tot_qt'),'products.pro_desc','quotes.rfq_no','categorys.cat_name','sub_categorys.sub_cat_name','categorys.primary_image')
         ->groupBy('quotes.rfq_no')
         ->orderBy('quotes.created_at','desc')
         ->whereNull('quotes.deleted_at');
         if(!empty($user_id))
         {
           $quotes = $quotes->where('quotes.user_id',$user_id);
         }
                         // ->toSql();
         $quotes = $quotes->get();
             // echo "<pre>";print_r($quotes);
             // exit();
         

         foreach ($quotes as $key => $value) {
           
          $quoteArr[$key]['quote_id'] = $value->id;
          $quoteArr[$key]['user_id'] = $value->user_id;
          $quoteArr[$key]['rfq_no'] = $value->rfq_no;
          $quoteArr[$key]['quantity'] = $value->tot_qt;
          $quoteArr[$key]['kam_status'] = $value->kam_status;
          $quoteArr[$key]['name'] = $value->name;
          $quoteArr[$key]['created_at'] = date('d-m-Y',strtotime($value->created_at));
          $quoteArr[$key]['cat_id'] = $value->cat_id;
          $quoteArr[$key]['product_id'] = $value->product_id;
          $quoteArr[$key]['cat_name'] = $value->cat_name;
          $quoteArr[$key]['sub_cat_name'] = $value->sub_cat_name;
          $quoteArr[$key]['pro_desc'] = $value->pro_desc;
          $quoteArr[$key]['status'] = $this->schedule_status($value->rfq_no);
          $quoteArr[$key]['dash_dt'] = date('jS F, Y',strtotime($value->created_at));
                    // $quoteArr[$key]['schedules'] = $value->schedules;
                    // $quoteArr[$key]['product'] = $value->product;
                    // $size = DB::table('sub_categorys')->where('pro_id',$value['product_id'])->select('pro_size')->first();
                    // $quoteArr[$key]['size'] = $size;
          
        }
                 // echo "<pre>";print_r($quoteArr);exit();


        \DB::commit();

        if(!empty($quoteArr))
        {
         return response()->json(['status'=>1,
          'message' =>config('global.sucess_msg'),
          'result' => $quoteArr],
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


          /*
      ----------------  quote update -------------------

  */

      public function getQuoteById($id)
      {
       \DB::beginTransaction();

       try{ 

         $chk_quote = Quote::where('rfq_no',$id)->count();
           // echo "<pre>";print_r($chk_quote);exit();
         if($chk_quote > 0)
         {
          $quoteArr = array();

          
          $rfq_number = (!empty($id)) ? $id : '';

          $quote = Quote::where('rfq_no',$id)->with('schedules')->with('product')->with('category')->with('subCategory')->orderBy('updated_at','desc')->get()->toArray();

          foreach ($quote as $key => $value) {
            
            if(!empty($value['schedules']))
            {
            $result[$key]['C'] = $value['sub_category']['C'];
            $result[$key]['Cr'] = $value['sub_category']['Cr'];
            $result[$key]['Phos'] = $value['sub_category']['Phos'];
            $result[$key]['S'] = $value['sub_category']['S'];
            $result[$key]['Si'] = $value['sub_category']['Si'];
            $result[$key]['cat_dese'] = $value['category']['cat_dese'];
            $result[$key]['cat_id'] = $value['category']['id'];
            $result[$key]['cat_name'] = $value['category']['cat_name'];
            $result[$key]['image_2_url'] = $value['category']['image_2'];
            $result[$key]['image_3_url'] = $value['category']['image_3'];
            $result[$key]['image_4_url'] = $value['category']['image_4'];
            $result[$key]['is_populer'] = $value['category']['is_populer'];
            $result[$key]['product_id'] = $value['product']['id'];
            $result[$key]['sizes'] = "";
            $result[$key]['slug'] = $value['product']['slug'];
            $result[$key]['status'] = $value['product']['status'];
            $result[$key]['primary_image_url'] = 'https://beas.in/mje-shop/storage/app/public/images/product/'.$value['category']['primary_image'];
            $result[$key]['schedule'] = $value['schedules'];
            $result[$key]['quote_id'] = $value['id'];
            $result[$key]['user_id'] = $value['user_id'];
            $result[$key]['rfq_no'] = $value['rfq_no'];
            $result[$key]['quantity'] = $value['quantity'];
            $result[$key]['st'] = $value['kam_status'];
          }
            
          }
              // echo "<pre>";print_r($result);exit();
          \DB::commit();
          if(!empty($result))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $result],config('global.success_status'));
          }
          else{

           return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

         }
       }
       else{

         return response()->json(['status'=>1,'message' =>'Quote do no exists','result' => []],config('global.success_status'));

       }


     }catch(\Exception $e){

       \DB::rollback();

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }


    /*
      ---------------- quote schedule status update  -------------------

  */
      public function updateQuoteSche(Request $request)
      {
         // echo "<pre>";print_r($request->all());exit();
        try{ 

          foreach ($request->all() as $key => $value) {
            
           $id = $value['id'];
           $status = $value['status'];
                 // echo "<pre>";print_r($id);exit();

           // $updated = QuoteSchedule::where('id',$id)->update(['quote_status' => $status]);
           $updated = DB::table('quote_schedules')->where('schedule_no',$id)->update(['quote_status' => $status]);
         }
         
         
         return response()->json(['status'=>1,
          'message' =>'status updated',
          'result' => $updated],
          config('global.success_status'));
         

       }catch(\Exception $e){

         return response()->json(['status'=>0,
          'message' =>'error',
          'result' => $e->getMessage()],
          config('global.failed_status'));
       }
       
     }


       /*
      ---------------- re quote id submit  -------------------

  */
      public function submitRequoteId(Request $request)
      {

        try{ 

          foreach ($request->all() as $key => $value) {

            $sche_id = $value;
            
            $ckh = DB::table('requotes')->where('schedule_id',$sche_id)->first();

            if(empty($ckh))
            {
              $updated = DB::table('requotes')->insert(['schedule_id' => $sche_id]);
              DB::table('quote_schedules')->where('schedule_no',$sche_id)->update(['quote_status' => 3]);
            }
            
            
          }
          
          
          return response()->json(['status'=>1,
            'message' =>'status updated',
            'result' => 'Re-quote updated'],
            config('global.success_status'));
          
              // echo "<pre>";print_r($quotes);exit();

        }catch(\Exception $e){

         return response()->json(['status'=>0,
          'message' =>'error',
          'result' => $e->getMessage()],
          config('global.failed_status'));
       }
       
     }



   /*
      ---------------- re-quote list -------------------

  */

      public function getRequoteList()
      {
       \DB::beginTransaction();

       try{ 

         if(Auth::check())
         {
           $user_id =  Auth::user()->id;;
           
         }
           // echo "<pre>";print_r($user_id);exit();
         $quoteArr = array();

         

         $quote = DB::table('requotes')->leftjoin('quote_schedules','requotes.schedule_id','quote_schedules.schedule_no')
         ->leftjoin('quotes','quote_schedules.quote_id','quotes.id')
         ->leftjoin('products','quotes.product_id','products.id')
         ->leftjoin('categorys','quotes.cat_id','categorys.id')
         ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
         ->select('quote_schedules.*','products.pro_desc','quotes.rfq_no','categorys.cat_name','sub_categorys.sub_cat_name','categorys.primary_image')
         ->whereNull('quote_schedules.deleted_at')
         ->where('quote_schedules.quote_status',3);
         if(!empty($user_id))
         {
           $quote = $quote->where('quotes.user_id',$user_id);
         }
         $quote = $quote->get();
              // echo "<pre>";print_r($quote);exit();


         foreach ($quote as $key => $value) {
           
           $quoteArr[$key]['schedule_no'] = $value->schedule_no;
           $quoteArr[$key]['pro_desc'] = $value->pro_desc;
           $quoteArr[$key]['pro_size'] = $value->pro_size;
           $quoteArr[$key]['rfq_no'] = $value->rfq_no;
           $quoteArr[$key]['quantity'] = $value->quantity;
           $quoteArr[$key]['kam_price'] = $value->kam_price;
           $quoteArr[$key]['expected_price'] = $value->expected_price;
           $quoteArr[$key]['cat_name'] = $value->cat_name;
           $quoteArr[$key]['sub_cat_name'] = $value->sub_cat_name;
           $quoteArr[$key]['p_image'] = asset('storage/app/public/images/product/'.$value->primary_image);
         }
         \DB::commit();
         if(!empty($quoteArr))
         {
          return response()->json(['status'=>1,'message' =>'success','result' => $quoteArr],config('global.success_status'));
        }
        else{

         return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

       }
       


     }catch(\Exception $e){

       \DB::rollback();

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }


    /*

        ----------------------------------  update requote ---------------------------

    */

        public function updateRequote(Request $request)
        {
             // echo "<pre>";print_r($request->all());exit();

         \DB::beginTransaction();

         try{ 
          foreach ($request->all() as $key => $value) {

            $quoteArr = array();
            
            $quote_sche = DB::table('quote_schedules')
            ->where('schedule_no',$value['sche_no'])
            ->whereNull('deleted_at')
            ->first();

            $quoteArr['quote_id'] = $quote_sche->quote_id;
            $quoteArr['schedule_no'] = $value['sche_no'];
            $quoteArr['quantity'] = $value['quantity'];
            $quoteArr['expected_price'] = $value['ex_price'];
            $quoteArr['kam_price'] = $value['kam_price'];
            $quoteArr['pro_size'] = $quote_sche->pro_size;
            $quoteArr['to_date'] = $quote_sche->to_date;
            $quoteArr['from_date'] = $quote_sche->from_date;
            $quoteArr['plant'] = $quote_sche->plant;
            $quoteArr['location'] = $quote_sche->location;
            $quoteArr['bill_to'] = $quote_sche->bill_to;
            $quoteArr['ship_to'] = $quote_sche->ship_to;
            $quoteArr['remarks'] = $quote_sche->remarks;
            $quoteArr['delivery'] = $quote_sche->delivery;
            $quoteArr['valid_till'] = $quote_sche->valid_till;
            $quoteArr['quote_status '] = $quote_sche->quote_status;

            
            $schedules = $value['schedules'];
               // echo "<pre>";print_r($quoteArr);exit();

            QuoteSchedule::where('schedule_no',$value['sche_no'])->delete();
            $quote_sch = QuoteSchedule::create($quoteArr);
            Quotedelivery::where('quote_sche_no',$value['sche_no'])->delete();
            foreach ($schedules as $key => $val) {
              
              
              $arr['quote_sche_no'] = $quoteArr['schedule_no'];
              $arr['qty'] = $val['quantity'];
              $arr['to_date'] = $val['to_date'];
              $arr['from_date'] = $val['from_date'];

              
              Quotedelivery::create($arr);
            }
              // echo "<pre>";print_r($quoteArr);exit();
          }

          \DB::commit();
          
          return response()->json(['status'=>1,
            'message' =>'success',
            'result' => 'Quotes updated'],
            config('global.success_status'));
          


        }catch(\Exception $e){

         \DB::rollback();

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

     }


        /*
      ---------------- delete  quote -------------------

  */
      public function deleteQuoteById($id)
      {   
        $arr = array();
        $sche_no = DB::table('quote_schedules')->where('quote_id',$id)->select('schedule_no')->get();
        foreach ($sche_no as $key => $value) {
          
          array_push($arr,$value->schedule_no);
        }

        DB::table('quote_deliveries')->whereIn('quote_sche_no',$arr)->delete();
        DB::table('quote_schedules')->where('quote_id',$id)->delete();
        DB::table('quotes')->where('id',$id)->delete();
        // return $arr;

        return response()->json(['status'=>1,
          'message' =>'success',
          'result' => 'Quote deleted'],
          config('global.success_status'));
      }




   /*
      ----------------  quote schedule list -------------------

  */

      public function getQuoteScheById($id)
      {
       \DB::beginTransaction();

       try{ 
         
         
         $chk_quote = Quote::where('rfq_no',$id)->count();
           // echo "<pre>";print_r($chk_quote);exit();
         if($chk_quote > 0)
         {
          $quoteArr = array();

          
          $rfq_number = (!empty($id)) ? $id : '';

              // $quote = Quote::where('rfq_no',$id)->with('schedules')->with('product')->with('category')->with('subCategory')->orderBy('updated_at','desc')->get()->toArray();
          $quoteArr =array();
          $quote_ids = DB::table('quotes')->where('rfq_no',$rfq_number)->whereNull('deleted_at')
          ->select('id','user_id')->get();
          foreach ($quote_ids as $key => $value) {
           
           array_push($quoteArr,$value->id);
           $userId = $value->user_id;
         }
         

         $quote = DB::table('quote_schedules')->where('quote_status',0)
         ->whereIn('quote_id',$quoteArr)->orderBy('id','desc')->get();
              // echo "<pre>";print_r($quote);exit();

         foreach ($quote as $key => $value) {
          
          
          
          $result[$key]['id']             = $value->id;
          $result[$key]['userId']         = $userId;
          $result[$key]['quote_id']       = $value->quote_id;
          $result[$key]['sizes']          = $value->pro_size;
          $result[$key]['schedule_no']    = $value->schedule_no;
          $result[$key]['created_at']     = $value->created_at;
          $result[$key]['quantity']       = $value->quantity;
          $result[$key]['kam_price']      = $value->kam_price;
          $result[$key]['expected_price'] = $value->expected_price;
          $result[$key]['delivery']       = $value->delivery;
          $result[$key]['plant']          = $value->plant;
          $result[$key]['location']       = $value->location;
          $result[$key]['bill_to']        = $value->bill_to;
          $result[$key]['ship_to']        = $value->ship_to;
          $result[$key]['rfq_no']         = $id;
          $result[$key]['valid_till']     = $value->valid_till;
          $result[$key]['to_date']        = $value->to_date;
          $result[$key]['from_date']      = $value->from_date;
          $result[$key]['remarks']        = $value->remarks;


          
          
          
          
        }
              // echo "<pre>";print_r($result);exit();
        \DB::commit();
        if(!empty($result))
        {
          return response()->json(['status'=>1,'message' =>'success','result' => $result],config('global.success_status'));
        }
        else{

         return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

       }
     }
     else{

       return response()->json(['status'=>1,'message' =>'Quote do no exists','result' => []],config('global.success_status'));

     }


   }catch(\Exception $e){

     \DB::rollback();

     return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
   }
 }



    /*
      ---------------- delete  quote schedules -------------------

  */
      public function deleteQuoteSche(Request $request)
      {   
        $arr = array();

        $id = $request->input('sche_id');
        $sche_no = DB::table('quote_schedules')->where('id',$id)->select('schedule_no','quantity','quote_id')->first();

        $quote = DB::table('quotes')->where('id',$sche_no->quote_id)->first();

        $tot = $quote->quantity - $sche_no->quantity;

        DB::table('quotes')->where('id',$sche_no->quote_id)->update(['quantity' => $tot]);
        

        DB::table('quote_deliveries')->where('quote_sche_no',$sche_no->schedule_no)->delete();
        DB::table('quote_schedules')->where('id',$id)->delete();


        $ct = DB::table('quote_schedules')->where('quote_id',$sche_no->quote_id)->get()->count();
        if($ct == 0)
        {
           DB::table('quotes')->where('id',$sche_no->quote_id)->delete();
        }
        
        // return $sche_no->schedule_no;exit();

        return response()->json(['status'=>1,
          'message' =>'success',
          'result' => 'Quote deleted'],
          config('global.success_status'));
      }



      /*
      ---------------- quote list  -------------------

  */
      public function getKamQuotesList()
      {  

        \DB::beginTransaction();

        try{ 


                // $user_id = Auth::user()->id;

         if(Auth::check())
         {
           $user_id =  Auth::user()->id;
           $user_state =  Auth::user()->state;
           
         }


                 // $res = $this->getQuoteHistory($user_id,$rfq_no);
         $quoteArr = array();    

             // $quotes = Quote::where('user_id',$user_id)->with('schedules')
           //   ->with('product')->orderBy('updated_at','desc')

         $quotes = DB::table('quotes')->leftjoin('users','quotes.user_id','users.id')
         ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
         ->leftjoin('products','quotes.product_id','products.id')
         ->leftjoin('categorys','quotes.cat_id','categorys.id')
         ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
         ->select('quotes.*','users.name',DB::raw("(sum(quotes.quantity)) as tot_qt"),'products.pro_desc','quotes.rfq_no','categorys.cat_name','sub_categorys.sub_cat_name','categorys.primary_image')
         ->groupBy('quotes.rfq_no')
         ->orderBy('quotes.created_at','desc')
         ->where('users.state',$user_state)
         ->whereNull('quotes.deleted_at');
                         // ->toSql();
         $quotes = $quotes->get();
             // echo "<pre>";print_r($quotes);
             // exit();
         

         foreach ($quotes as $key => $value) {
           
          $quoteArr[$key]['quote_id'] = $value->id;
          $quoteArr[$key]['user_id'] = $value->user_id;
          $quoteArr[$key]['rfq_no'] = $value->rfq_no;
          $quoteArr[$key]['quantity'] = $value->tot_qt;
          $quoteArr[$key]['kam_status'] = $value->kam_status;
          $quoteArr[$key]['name'] = $value->name;
          $quoteArr[$key]['created_at'] = date('jS F, Y',strtotime($value->created_at));
          $quoteArr[$key]['cat_id'] = $value->cat_id;
          $quoteArr[$key]['product_id'] = $value->product_id;
          $quoteArr[$key]['cat_name'] = $value->cat_name;
          $quoteArr[$key]['sub_cat_name'] = $value->sub_cat_name;
          $quoteArr[$key]['pro_desc'] = $value->pro_desc;
          $quoteArr[$key]['status'] = $this->schedule_status($value->rfq_no);
                    // $quoteArr[$key]['schedules'] = $value->schedules;
                    // $quoteArr[$key]['product'] = $value->product;
                    // $size = DB::table('sub_categorys')->where('pro_id',$value['product_id'])->select('pro_size')->first();
                    // $quoteArr[$key]['size'] = $size;
          
        }
                 // echo "<pre>";print_r($quoteArr);exit();


        \DB::commit();

        if(!empty($quoteArr))
        {
         return response()->json(['status'=>1,
          'message' =>config('global.sucess_msg'),
          'result' => $quoteArr],
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



   public function schedule_status($rfq_no)
   { 
       $sts = array();
       $resa = DB::table('quotes')
       ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
       ->select('quote_schedules.quote_status')
       ->where('quotes.rfq_no',$rfq_no)
       ->whereNull('quotes.deleted_at')
       ->get();

       foreach($resa as $k => $v)
       {
           array_push($sts,$v->quote_status);
       }
       
       if (in_array(1, $sts) && !in_array(4, $sts))
        {
           $st = "Accepted";
        }
       elseif (in_array(4, $sts))
        {
           $st = "Delivered";
        }
       else
        {
           $st = "Pending";
        }
      
       return $st;
   } 

   /*--------------------------view remarks --------------------------------------------*/


    public function viewRemarks($rfq)
    {
         \DB::beginTransaction();

       try{ 

         
           // echo "<pre>";print_r($user_id);exit();
         $quoteArr = array();

         

         $quote = DB::table('quote_schedules')->whereNotNull('deleted_at')->where('schedule_no',$rfq)
         ->select('remarks','kamsRemarks','created_at')->get();
              // echo "<pre>";print_r($quote);exit();


         foreach ($quote as $key => $value) {
           
           $quoteArr[$key]['remarks'] = $value->remarks;
           $quoteArr[$key]['kamsRemarks'] = $value->kamsRemarks;
           $quoteArr[$key]['created_at'] = $value->created_at;
           
         
         }
         \DB::commit();
         if(!empty($quoteArr))
         {
          return response()->json(['status'=>1,'message' =>'success','result' => $quoteArr],config('global.success_status'));
        }
        else{

         return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

       }
       


     }catch(\Exception $e){

       \DB::rollback();

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
    }
   /*--------------------------view remarks --------------------------------------------*/  

  /*
      ----------------  get_quote_po_by_id -------------------

  */

      public function getPoQuoteById($id)
      {
       \DB::beginTransaction();

       try{ 

         $chk_quote = Quote::where('rfq_no',$id)->count();
           // echo "<pre>";print_r($chk_quote);exit();
         if($chk_quote > 0)
         {
          $quoteArr = array();

          
          $rfq_number = (!empty($id)) ? $id : '';

          // $quote = Quote::where('rfq_no',$id)->with('schedules')->with('product')->with('category')->with('subCategory')->orderBy('updated_at','desc')->get()->toArray();

           $quote = DB::table('quotes')
           ->leftjoin('users','quotes.user_id','users.id')
           ->leftjoin('products','quotes.product_id','products.id')
           ->leftjoin('categorys','quotes.cat_id','categorys.id')
           ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
           ->select('quotes.rfq_no','quotes.user_id','quotes.id as qid','products.slug','products.status','categorys.*','sub_categorys.*','users.id','products.id as pid','categorys.id as cid','quotes.quantity')
           ->orderBy('quotes.updated_at','desc')
           ->where('quotes.rfq_no',$id)
           ->whereNull('quotes.deleted_at')
           ->get()->toArray();
           // echo "<pre>";print_r($quote);exit();
          foreach ($quote as $key => $value) {
            
            $result[$key]['C'] = $value->C;
            $result[$key]['Cr'] = $value->Cr;
            $result[$key]['Phos'] = $value->Phos;
            $result[$key]['S'] = $value->S;
            $result[$key]['Si'] = $value->Si;
            $result[$key]['cat_dese'] = $value->cat_dese;
            $result[$key]['cat_id'] = $value->cid;
            $result[$key]['cat_name'] = $value->cat_name;
            $result[$key]['image_2_url'] = $value->image_2;
            $result[$key]['image_3_url'] = $value->image_3;
            $result[$key]['image_4_url'] = $value->image_4;
            $result[$key]['is_populer'] = $value->is_populer;
            $result[$key]['product_id'] = $value->pid;
            $result[$key]['sizes'] = $value->pro_size;
            $result[$key]['slug'] = $value->slug;
            $result[$key]['status'] = $value->status;
            $result[$key]['primary_image_url'] = 'https://beas.in/mje-shop/storage/app/public/images/product/'.$value->primary_image;
            $result[$key]['schedule'] = $this->getSchedules($value->qid);
            $result[$key]['quote_id'] = $value->qid;
            $result[$key]['user_id'] = $value->user_id;
            $result[$key]['rfq_no'] = $value->rfq_no;
            $result[$key]['quantity'] = $value->quantity;
            
          }
              // echo "<pre>";print_r($result);exit();
          \DB::commit();
          if(!empty($result))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $result],config('global.success_status'));
          }
          else{

           return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

         }
       }
       else{

         return response()->json(['status'=>1,'message' =>'Quote do no exists','result' => []],config('global.success_status'));

       }


     }catch(\Exception $e){

       \DB::rollback();

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }


    public function getSchedules($qid)
    {
        $quote_sches = array();

        $res = DB::table('quote_schedules')->where('quote_id',$qid)->whereNull('deleted_at')->get();

        foreach ($res as $key => $value) {
           
           $quote_sches[$key]['id'] = $value->id;
           $quote_sches[$key]['schedule_no'] = $value->schedule_no;
           $quote_sches[$key]['quantity'] = $value->quantity;
           $quote_sches[$key]['pro_size'] = $value->pro_size;
           $quote_sches[$key]['to_date'] = $value->to_date;
           $quote_sches[$key]['from_date'] = $value->from_date;
           $quote_sches[$key]['kam_price'] = $value->kam_price;
           $quote_sches[$key]['expected_price'] = $value->expected_price;
           $quote_sches[$key]['plant'] = $value->plant;
           $quote_sches[$key]['location'] = $value->location;
           $quote_sches[$key]['bill_to'] = $value->bill_to;
           $quote_sches[$key]['ship_to'] = $value->ship_to;
           $quote_sches[$key]['remarks'] = $value->remarks;
           $quote_sches[$key]['kamsRemarks'] = $value->kamsRemarks;
           $quote_sches[$key]['delivery'] = $value->delivery;
           $quote_sches[$key]['valid_till'] = $value->valid_till;
           $quote_sches[$key]['quote_status'] = $value->quote_status;
      
           

        }

        return $quote_sches;
    }


    /*---------------------------- submit PO -----------------------------------------*/
      
      public function submitPo(Request $request)
      {

         // echo "<pre>";print_r($request->all());exit();

       try{ 

            

            $poArr = array();

            $poArr['rfq_no'] = $request->input('rfq_no');
            $poArr['po_no'] = $request->input('po_no');

            $files = $request->file('letterhead');
            if(!empty($files))
            {

              $name = time().$files->getClientOriginalName(); 
              $files->storeAs("public/images/letterheads",$name);  
              $poArr['letterhead'] = $name;
            }

            $date =  date_create($request->input('po_date'));
            $po_dt = date_format($date,"Y-m-d");
            $poArr['po_date'] = $po_dt;
            $poArr['status'] = 2;
          
            // echo "<pre>";print_r($poArr);exit();
         
            Order::create($poArr);

            return response()->json(['status'=>1,
              'message' =>'success',
              'result' => 'P.O created'],
              config('global.success_status'));



      }catch(\Exception $e){

              return response()->json(['status'=>0,
                'message' =>'error',
                'result' => $e->getMessage()],
                config('global.failed_status'));
          }
      }

    /*-----------------------------------------------------------------------------------*/
    

      /*
      ----------------  get_quote_po_by_id -------------------

  */

      public function getPoById($id)
      {
       \DB::beginTransaction();

       try{ 

         $chk_quote = Quote::where('rfq_no',$id)->count();
           // echo "<pre>";print_r($chk_quote);exit();
         if($chk_quote > 0)
         {
          $quoteArr = array();

          
          $rfq_number = (!empty($id)) ? $id : '';

          // $quote = Quote::where('rfq_no',$id)->with('schedules')->with('product')->with('category')->with('subCategory')->orderBy('updated_at','desc')->get()->toArray();

           $quote = DB::table('orders')
           ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
           ->leftjoin('users','quotes.user_id','users.id')
           ->leftjoin('products','quotes.product_id','products.id')
           ->leftjoin('categorys','quotes.cat_id','categorys.id')
           ->leftjoin('sub_categorys','categorys.id','sub_categorys.cat_id')
           ->select('quotes.rfq_no','quotes.user_id','quotes.id as qid','products.slug','products.status','categorys.*','sub_categorys.*','users.id','products.id as pid','categorys.id as cid','quotes.quantity','orders.letterhead','orders.po_no','orders.po_date')
           ->orderBy('quotes.updated_at','desc')
           ->where('orders.rfq_no',$id)
           ->whereNull('quotes.deleted_at')
           ->get()->toArray();
           // echo "<pre>";print_r($quote);exit();
          foreach ($quote as $key => $value) {
            
            $result[$key]['C'] = $value->C;
            $result[$key]['Cr'] = $value->Cr;
            $result[$key]['Phos'] = $value->Phos;
            $result[$key]['S'] = $value->S;
            $result[$key]['Si'] = $value->Si;
            $result[$key]['cat_dese'] = $value->cat_dese;
            $result[$key]['cat_id'] = $value->cid;
            $result[$key]['cat_name'] = $value->cat_name;
            $result[$key]['image_2_url'] = $value->image_2;
            $result[$key]['image_3_url'] = $value->image_3;
            $result[$key]['image_4_url'] = $value->image_4;
            $result[$key]['is_populer'] = $value->is_populer;
            $result[$key]['product_id'] = $value->pid;
            $result[$key]['sizes'] = $value->pro_size;
            $result[$key]['slug'] = $value->slug;
            $result[$key]['status'] = $value->status;
            $result[$key]['primary_image_url'] = 'https://beas.in/mje-shop/storage/app/public/images/product/'.$value->primary_image;
            $result[$key]['quote_id'] = $value->qid;
            $result[$key]['user_id'] = $value->user_id;
            $result[$key]['rfq_no'] = $value->rfq_no;
            $result[$key]['quantity'] = $value->quantity;
            $result[$key]['po_no'] = $value->po_no;
            $result[$key]['letterhead'] = asset('storage/app/public/images/letterheads/'.$value->letterhead);
            $date =  date_create($value->po_date);
            $po_dt = date_format($date,"d-m-Y");
            $result[$key]['po_date'] = $po_dt;
            $result[$key]['schedule'] = $this->getSchedules($value->qid);
            
            
          }
              // echo "<pre>";print_r($result);exit();
          \DB::commit();
          if(!empty($result))
          {
            return response()->json(['status'=>1,'message' =>'success','result' => $result],config('global.success_status'));
          }
          else{

           return response()->json(['status'=>1,'message' =>'success','result' => 'Quote not updated'],config('global.success_status'));

         }
       }
       else{

         return response()->json(['status'=>1,'message' =>'Quote do no exists','result' => []],config('global.success_status'));

       }


     }catch(\Exception $e){

       \DB::rollback();

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
   }


   /*------------------------get all po --------------------------------------*/

      public function getPoAll()
      {
           \DB::beginTransaction();
          try{

            $user_id =  Auth::user()->id;


             $result = $this->allPo($user_id);

            \DB::commit();

                if(!empty($result))
                {
                  return response()->json(['status'=>1,
                    'message' =>'success',
                    'result' => $result],
                    config('global.success_status'));
                }
        }
          catch(\Exception $e){

              \DB::rollback();

             return response()->json(['status'=>0,
              'message' =>'error',
              'result' => $e->getMessage()],
              config('global.failed_status'));

          }
      
    }


   /*------------------------------------------------------------------------------*/



      /*------------------------get all po kam--------------------------------------*/

      public function getPoAllKam()
      {
           \DB::beginTransaction();
          try{

            $user_id =  "";
            $user_state =  Auth::user()->state;


             $result = $this->allPo($user_id,$user_state);

            \DB::commit();

                if(!empty($result))
                {
                  return response()->json(['status'=>1,
                    'message' =>'success',
                    'result' => $result],
                    config('global.success_status'));
                }
        }
          catch(\Exception $e){

              \DB::rollback();

             return response()->json(['status'=>0,
              'message' =>'error',
              'result' => $e->getMessage()],
              config('global.failed_status'));

          }
      
    }


   /*------------------------------------------------------------------------------*/

    public function allPo($user_id=NULL,$user_state=NULL)
    {
           $quote = DB::table('orders')
           ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
           ->leftjoin('quote_schedules','quotes.id','quote_schedules.quote_id')
           ->leftjoin('users','quotes.user_id','users.id')    
           ->select('quotes.rfq_no','quotes.user_id','orders.letterhead','orders.po_no','orders.po_date','users.name','orders.status',DB::raw("(sum(quotes.quantity)) as tot_qt"))
           ->orderBy('quotes.updated_at','desc')
           ->groupBy('quotes.rfq_no');
           if(!empty($user_id))
           {
              $quote = $quote->where('quotes.user_id',$user_id);
            }
           if(!empty($user_state))
           {

              $quote = $quote->where('users.state',$user_state);
           }
           $quote = $quote->whereNull('quotes.deleted_at')
           ->get()->toArray();
           // echo "<pre>";print_r($quote);exit();

          if(!empty($quote))
          {
          foreach ($quote as $key => $value) {
            
            $result[$key]['po_no'] = $value->po_no;
            $result[$key]['user'] = $value->name;
            $result[$key]['rfq_no'] = $value->rfq_no;
            $result[$key]['quantity'] = $value->tot_qt;
            $date =  date_create($value->po_date);
            $po_dt = date_format($date,"d/m/Y");
            $result[$key]['po_date'] = $po_dt;
            $result[$key]['status'] = $value->status;

          }
        }
        else{
          $result = [];
        }

          return $result;
    }


    }
