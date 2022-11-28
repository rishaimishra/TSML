<?php

namespace App\Http\Controllers\Api\Modules\Complain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSubCategory;
use App\Models\PriceManagement;
use App\Models\PriceCalculation;
use App\Models\ThresholdLimits;
use App\Models\Freights;
use App\Models\ComplainCategory;
use App\Models\ComplainSubCategory;
use App\Models\ComplainSubCategory2;
use App\Models\ComplainSubCategory3;
use App\Models\ComplainRemarks;
use App\Models\ComplainMain;
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 


class ComplainController extends Controller
{
    /**
     * This is for add storeComplainCategory. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeComplainCategory(Request $request)
    {
      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'com_cate_name'        => 'required', 
          ]);

          if ($validator->fails()) { 
              return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          }

         
          $input['com_cate_name'] = $request->com_cate_name; 

          $freightsData = ComplainCategory::create($input);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Complain category added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for get complain category. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getComplainCategory(Request $request)
    {
      try{ 
            $ComplainCategoryData = ComplainCategory::get();  
             
            if (count($ComplainCategoryData)>0) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $ComplainCategoryData],200);
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
     * This is for store complain sub category. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeComplainSubCategory(Request $request)
    {
      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'com_cate_id'        => 'required',
            'com_sub_cate_name'        => 'required', 
          ]);

          if ($validator->fails()) { 
            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          } 
         
          $input['com_cate_id'] = $request->com_cate_id;
          $input['com_sub_cate_name'] = $request->com_sub_cate_name; 

          $freightsData = ComplainSubCategory::create($input);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Complain sub category added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for get complain category. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getComplainSubCategory($com_cate_id)
    {
      try{ 
            $ComplainSubCategoryData = ComplainSubCategory::where('com_cate_id',$com_cate_id)->orderBy('id','desc')->get(); 
             
            if (count($ComplainSubCategoryData)>0) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $ComplainSubCategoryData],200);
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
     * This is for add storeComplainSubCategory2. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeComplainSubCategory2(Request $request)
    {
      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'com_cate_id'        => 'required',
            'com_sub_cate_id'        => 'required',
            'com_sub_cate2_name'        => 'required', 
          ]);

          if ($validator->fails()) { 
              return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          }

         
          $input['com_cate_id'] = $request->com_cate_id;
          $input['com_sub_cate_id'] = $request->com_sub_cate_id;
          $input['com_sub_cate2_name'] = $request->com_sub_cate2_name;
           
          $freightsData = ComplainSubCategory2::create($input);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Complain sub category 2 added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for add storeComplainSubCategory2. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getComplainSubCategory2($com_sub_cate_id)
    {
      try{ 
            $ComplainSubCategory2Data = ComplainSubCategory2::where('com_sub_cate_id',$com_sub_cate_id)->orderBy('id','desc')->get();   
             
             
            if (count($ComplainSubCategory2Data)>0) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $ComplainSubCategory2Data],200);
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
     * This is for add storeComplainSubCategory3. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeComplainSubCategory3(Request $request)
    {
      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'com_cate_id'        => 'required',
            'com_sub_cate_id'        => 'required',
            'com_sub_cate_2id'        => 'required',
            'com_sub_cate3_name'        => 'required', 
          ]);

          if ($validator->fails()) { 
              return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          }

         
          $input['com_cate_id'] = $request->com_cate_id;
          $input['com_sub_cate_id'] = $request->com_sub_cate_id;
          $input['com_sub_cate_2id'] = $request->com_sub_cate_2id;
          $input['com_sub_cate3_name'] = $request->com_sub_cate3_name;
           
          $freightsData = ComplainSubCategory3::create($input);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Complain sub category 2 added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for add storeComplainSubCategory2. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getComplainSubCategory3($com_sub_cate_2id)
    {
      try{ 
          $ComplainSubCategory3Data = ComplainSubCategory3::where('com_sub_cate_2id',$com_sub_cate_2id)->orderBy('id','desc')->get();   
             
             
          if (count($ComplainSubCategory3Data)>0) {
             return response()->json(['status'=>1,'message' =>'success.','result' => $ComplainSubCategory3Data],200);
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
     * This is for add storeComplain Main. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeComplainMain(Request $request)
    {

      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'com_cate_id'        => 'required',
            'com_sub_cate_id'        => 'required',
            'com_sub_cate_2id'        => 'required',
            'com_sub_cate_3id'        => 'required',
            'po_number'        => 'required',
            'po_date'        => 'required',
            'user_id'        => 'required', 
          ]);

          if ($validator->fails()) { 
            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          } 
         
          $input['com_cate_id'] = $request->com_cate_id;
          $input['com_sub_cate_id'] = $request->com_sub_cate_id;
          $input['com_sub_cate_2id'] = $request->com_sub_cate_2id;
          $input['com_sub_cate_3id'] = $request->com_sub_cate_3id;
          $input['po_number'] = $request->po_number;
          $input['po_date'] = date("Y-m-d", strtotime($request->po_date));
          $input['user_id'] = $request->user_id;

         
          
         

        // dd($input);
 
          $complainData = ComplainMain::create($input); 

          if ($request->hasFile('cust_complain_file'))
          {  

            $image = $request->cust_complain_file; 

            $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
            Storage::putFileAs('public/images/complain/', $image, $filename);

            $remarksinput['cust_com_file'] = $filename; 
          }

          $remarksinput['complain_id'] = $complainData->id;
          $remarksinput['customer_remarks'] = $request->customer_remarks;
          // dd($remarksinput);

          $freightsData = ComplainRemarks::create($remarksinput);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Complain added successfully','result' => 'success'],config('global.success_status'));
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
     * This is for add remarksReplay. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function remarksReplay(Request $request)
    {
      \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'complain_id'        => 'required', 
          ]);

          if ($validator->fails()) { 
              return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          } 
          
          if ($request->customer_remarks) {

            $input['complain_id'] = $request->complain_id;
            $input['customer_remarks'] = $request->customer_remarks;

            if ($request->hasFile('cust_complain_file'))
            {  

              $image = $request->cust_complain_file; 

              $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
              Storage::putFileAs('public/images/complain/', $image, $filename);

              $input['cust_com_file'] = $filename; 
            }
            // dd($input);
            $RemarksData = ComplainRemarks::create($input);
          }
          if ($request->kam_remarks) {
            $input['complain_id'] = $request->complain_id;
            $input['kam_remarks'] = $request->kam_remarks;

            if ($request->hasFile('kam_complain_file'))
            {  

              $image = $request->kam_complain_file; 

              $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
              Storage::putFileAs('public/images/complain/', $image, $filename);

              $input['kam_com_file'] = $filename; 
            }

            $RemarksData = ComplainRemarks::create($input);
          } 

          \DB::commit();

          if($RemarksData)
          {
            return response()->json(['status'=>1,'message' =>'Remarks added successfully','result' => $RemarksData],config('global.success_status'));
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
     * This is for add getComplainCategory. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getComplainListKam(Request $request)
    {
      try{ 
          $ComplainListData = DB::table('complain_main')
          ->leftjoin('complain_categorys','complain_main.com_cate_id','complain_categorys.id')
          ->leftjoin('complain_sub_categorys','complain_main.com_sub_cate_id','complain_sub_categorys.id')
          ->leftjoin('complain_sub_categorys2','complain_main.com_sub_cate_2id','complain_sub_categorys2.id')
          ->leftjoin('complain_sub_categorys3','complain_main.com_sub_cate_3id','complain_sub_categorys3.id')
           ->leftjoin('users','complain_main.user_id','users.id')
           ->leftjoin('orders','complain_main.po_number','orders.po_no')
          ->join('complain_remarks','complain_main.id','complain_remarks.complain_id')
         
         ->select('complain_main.id','users.name as customer_name','complain_main.po_number','complain_main.po_date','complain_main.created_at','complain_main.file','complain_categorys.com_cate_name','complain_sub_categorys.com_sub_cate_name','complain_sub_categorys2.com_sub_cate2_name','complain_sub_categorys3.com_sub_cate3_name','complain_remarks.customer_remarks','orders.cus_po_no'); 
          
         
         if(!empty($request->user_id))
           {
             $ComplainListData = $ComplainListData->where('complain_main.user_id',$request->user_id);
           }
            
           $ComplainListData = $ComplainListData->orderBy('complain_remarks.created_at','desc')->groupBy('complain_remarks.complain_id')->get();
            



           $complainlist = [];
          
          foreach ($ComplainListData as $ComplainList) {

            $data['complain_id'] = $ComplainList->id;
            $data['customer_name'] = $ComplainList->customer_name;
            $data['created_at'] = $ComplainList->created_at;
            $data['com_cate_name'] = $ComplainList->com_cate_name;
            $data['com_sub_cate_name'] = $ComplainList->com_sub_cate_name; 
            $data['com_sub_cate2_name'] = $ComplainList->com_sub_cate2_name;
            $data['com_sub_cate3_name'] = $ComplainList->com_sub_cate3_name;
            $data['po_number'] = $ComplainList->po_number;
            $data['cus_po_no'] = $ComplainList->cus_po_no;
            $data['po_date'] = $ComplainList->po_date;
            $data['remarks'] = $ComplainList->customer_remarks;

            $data['com_file_url'] = $this->complainFlain($ComplainList->id);

           

            $complainlist[] = $data;
          }
          
          if (count($ComplainListData)>0) {
             return response()->json(['status'=>1,'message' =>'success.','result' => $complainlist],200);
          }
          else{

             return response()->json(['status'=>1,'message' =>'No data found','result' => []],config('global.success_status'));

          } 
          
          }catch(\Exception $e){
              $response['error'] = $e->getMessage();
              return response()->json([$response]);
          }


    }

    public function complainFlain($ComplainList)
    {
        $getComplainfile = DB::table('complain_remarks')
                            ->where('complain_id',$ComplainList)
                            ->select('complain_remarks.id',
                                      'complain_remarks.cust_com_file',
                                      'complain_remarks.kam_com_file')
                            ->get();

        $cust_com_filearr = [];

        foreach ($getComplainfile as $key => $value) 
        { 
            if(!empty($value->cust_com_file)){
              array_push( $cust_com_filearr, asset('storage/app/public/images/complain/'.$value->cust_com_file));
            }
 
        }
        $getComplainfiledata["cust_com_file"]  = $cust_com_filearr;

        $kam_com_filearr = [];

        foreach ($getComplainfile as $key => $value) 
        { 
          if(!empty($value->kam_com_file)){
            array_push( $kam_com_filearr, asset('storage/app/public/images/complain/'.$value->kam_com_file));
          }
 
        }
        $getComplainfiledata["kam_com_file"]  = $kam_com_filearr; 

        return $getComplainfiledata;
    }

    /**
     * This is for add get Complain Details. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function complainDetailsKam($po_number)
    {
      try{
            $ComplainListData = DB::table('complain_main')
            ->leftjoin('complain_categorys','complain_main.com_cate_id','complain_categorys.id')
            ->leftjoin('complain_sub_categorys','complain_main.com_sub_cate_id','complain_sub_categorys.id')
            ->leftjoin('complain_sub_categorys2','complain_main.com_sub_cate_2id','complain_sub_categorys2.id')
            ->leftjoin('complain_sub_categorys3','complain_main.com_sub_cate_3id','complain_sub_categorys3.id')
            ->leftjoin('users','complain_main.user_id','users.id')
            ->select('complain_main.id','users.name as customer_name','complain_main.po_number','complain_main.po_date','complain_main.created_at','complain_main.file','complain_main.closed_status','complain_categorys.com_cate_name','complain_sub_categorys.com_sub_cate_name','complain_sub_categorys2.com_sub_cate2_name','complain_sub_categorys3.com_sub_cate3_name')
            ->where('complain_main.po_number',$po_number)
            ->first(); 

          $data['complain_id'] = $ComplainListData->id;
          $data['customer_name'] = $ComplainListData->customer_name;
          $data['created_at'] = $ComplainListData->created_at;
          $data['complain_status'] = $ComplainListData->closed_status;
          $data['com_cate_name'] = $ComplainListData->com_cate_name;
          $data['com_sub_cate_name'] = $ComplainListData->com_sub_cate_name; 
          $data['com_sub_cate2_name'] = $ComplainListData->com_sub_cate2_name;
          $data['com_sub_cate3_name'] = $ComplainListData->com_sub_cate3_name;
          $data['po_number'] = $ComplainListData->po_number;
          $data['po_date'] = $ComplainListData->po_date;

          if ($ComplainListData->file) 
          {

            $data['file_url'] = asset('storage/app/public/images/complain/'.$ComplainListData->file);
          }
          else
          {
            $data['file_url'] =  null;
          } 
          
          $remarksData = ComplainRemarks::where('complain_id',$ComplainListData->id)->get();
             
          if (!empty($ComplainListData)){
            return response()->json(['status'=>1,'message' =>'success','result' =>$data,'remarksData' =>$remarksData],200);
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
     * This is for add get Complain Details. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function complainDetails($complainId)
    {
      try{
            $ComplainListData = DB::table('complain_main')
            ->leftjoin('complain_categorys','complain_main.com_cate_id','complain_categorys.id')
            ->leftjoin('complain_sub_categorys','complain_main.com_sub_cate_id','complain_sub_categorys.id')
            ->leftjoin('complain_sub_categorys2','complain_main.com_sub_cate_2id','complain_sub_categorys2.id')
            ->leftjoin('complain_sub_categorys3','complain_main.com_sub_cate_3id','complain_sub_categorys3.id')
         
            ->select('complain_main.id','complain_main.customer_name','complain_main.po_number','complain_main.po_date','complain_main.created_at','complain_main.file','complain_main.closed_status','complain_categorys.com_cate_name','complain_sub_categorys.com_sub_cate_name','complain_sub_categorys2.com_sub_cate2_name','complain_sub_categorys3.com_sub_cate3_name')
            ->where('complain_main.id',$complainId)
            ->first(); 

          $data['complain_id'] = $ComplainListData->id;
          $data['customer_name'] = $ComplainListData->customer_name;
          $data['created_at'] = $ComplainListData->created_at;
          $data['complain_status'] = $ComplainListData->closed_status;
          $data['com_cate_name'] = $ComplainListData->com_cate_name;
          $data['com_sub_cate_name'] = $ComplainListData->com_sub_cate_name; 
          $data['com_sub_cate2_name'] = $ComplainListData->com_sub_cate2_name;
          $data['com_sub_cate3_name'] = $ComplainListData->com_sub_cate3_name;
          $data['po_number'] = $ComplainListData->po_number;
          $data['po_date'] = $ComplainListData->po_date;

          if ($ComplainListData->file) 
          {

            $data['file_url'] = asset('storage/app/public/images/complain/'.$ComplainListData->file);
          }
          else
          {
            $data['file_url'] =  null;
          } 
          
          $remarksData = ComplainRemarks::where('complain_id',$complainId)->get();
             
          if (!empty($ComplainListData)){
            return response()->json(['status'=>1,'message' =>'success','result' =>$data,'remarksData' =>$remarksData],200);
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
     * This is for closed complain.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function closedRemarks($id)
    {  
        $getComplain = ComplainMain::where('id',$id)->first();  

        if(!empty($getComplain))
        { 
        $input['closed_status'] = 2; //2=> Open/1=>Closed. 

        $updateComplain = ComplainMain::where('id',$getComplain->id)->update($input);
 
          return response()->json(['status'=>1,'message' =>'Complain status inactive successfully.']);          
        }
        else
        {
          return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
        
    }

    
}
