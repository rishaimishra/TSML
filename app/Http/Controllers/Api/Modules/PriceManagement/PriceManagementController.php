<?php

namespace App\Http\Controllers\Api\Modules\PriceManagement;

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
use JWTAuth;
use Validator;
use File; 
use Storage;
use Response;
use DB; 


class PriceManagementController extends Controller
{
   /**
     * This is for add Threshold price. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function storeThreshold(Request $request)
    {

        \DB::beginTransaction();

        try{

          $validator = Validator::make($request->all(), [              
            'Price_Premium'        => 'required', 
            'Misc_Expense'     => 'required',
            'Delivery_Cost'        => 'required',
            'Credit_Cost_For_30_days'        => 'required', 
            'Credit_Cost_For_40_days'     => 'required', 
            'CAM_Discount'     => 'required',  
          ]);

          if ($validator->fails()) { 
              return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
          }

         
          $input['Price_Premium'] = $request->Price_Premium;
          $input['Misc_Expense'] = $request->Misc_Expense;
          $input['Delivery_Cost'] = $request->Delivery_Cost;
          $input['Credit_Cost_For_30_days'] = $request->Credit_Cost_For_30_days;
          $input['Credit_Cost_For_40_days'] = $request->Credit_Cost_For_40_days; 
          $input['CAM_Discount'] = $request->CAM_Discount;

            // dd($input);

          $freightsData = ThresholdLimits::create($input);

          \DB::commit();

          if($freightsData)
          {
            return response()->json(['status'=>1,'message' =>'Threshold price added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for get Threshold price. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getThresholdPrice(Request $request)
    {
      try{ 
            $ThresholdPriceData = ThresholdLimits::get();  

             
            foreach ($ThresholdPriceData as $ThresholdData) {
              # code...
            
            $data['price_premium'] = $ThresholdData->Price_Premium;
            $data['misc_expense'] = $ThresholdData->Misc_Expense;
            $data['delivery_cost'] = $ThresholdData->Delivery_Cost;
            $data['credit_cost_for30_days'] = $ThresholdData->Credit_Cost_For_30_days;
            $data['credit_cost_for45_days'] = $ThresholdData->Credit_Cost_For_40_days; 
            $data['cam_discount'] = $ThresholdData->CAM_Discount;
            }
            if (count($ThresholdPriceData)>0) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $data],200);
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
     * This is for add product price. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function manageProPrice(Request $request)
    {
    

      \DB::beginTransaction();

      try{

        $validator = Validator::make($request->all(), [
          'user_id'        => 'required', 
          'BPT_Price'     => 'required',
          'Price_Premium'        => 'required', 
          'Misc_Expense'     => 'required',
          'Credit_Cost_For_30_days'        => 'required', 
          'Credit_Cost_For_40_days'     => 'required',
          'Interest_Rate'        => 'required', 
          'CAM_Discount'     => 'required',  
        ]);

        if ($validator->fails()) { 
            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
        }

        $input['user_id'] = $request->user_id;
        $input['BPT_Price'] = $request->BPT_Price;
        $input['Price_Premium'] = $request->Price_Premium;
        $input['Misc_Expense'] = $request->Misc_Expense;
        $input['Credit_Cost_For_30_days'] = $request->Credit_Cost_For_30_days;
        $input['Credit_Cost_For_40_days'] = $request->Credit_Cost_For_40_days;
        $input['Interest_Rate'] = $request->Interest_Rate;
        $input['CAM_Discount'] = $request->CAM_Discount;

          // dd($input);

        $freightsData = PriceCalculation::create($input);

        \DB::commit();

        if($freightsData)
        {
          return response()->json(['status'=>1,'message' =>'Price added successfully','result' => $freightsData],config('global.success_status'));
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
     * This is for get product price. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getProPrice(Request $request)
    {
        

        try{ 
            $priceData = PriceCalculation::where('user_id',$request->user_id)->first();

            $getdeliverycost = Freights::where('pickup_from',$request->pickup_from)->where('location',$request->location)->first(); 


            $data['bpt_price'] = $priceData->BPT_Price;
            $data['price_premium'] = $priceData->Price_Premium;
            $data['misc_expense'] = $priceData->Misc_Expense;
            $data['delivery_cost'] = $getdeliverycost->freight_charges;
            $data['credit_cost_for30_days'] = $priceData->Credit_Cost_For_30_days;
            $data['credit_cost_for45_days'] = $priceData->Credit_Cost_For_40_days;
            $data['interest_rate'] = $priceData->Interest_Rate;
            $data['cam_discount'] = $priceData->CAM_Discount;
           
            if (!empty($priceData)) {
               return response()->json(['status'=>1,'message' =>'success.','result' => $data],200);
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
     * This is for show product list. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function getProductList(Request $request)
    {    

    	try{         
            $data = Product::orderBy('id','desc')->get();

            $prolist = [];
            foreach ($data as $key => $value) 
            {   
              $prodata['product_id'] = $value->id;
	            $prodata['product_title'] = $value->pro_name;
	            $prodata['product_desc'] = $value->pro_desc;
	            $prodata['product_status'] = $value->status; 
	              
	            $prolist[] = $prodata;
            } 
              
             return response()->json(['status'=>1,'message' =>'success.','result' => $prolist],200); 
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            }
    }

    /**
     * This is for show category list. 
     * @param  \App\Product  $category 
     * @return \Illuminate\Http\Response
    */
    public function getCategoryList($proId)
    {
    	try{         
            $data = Category::where('product_id',$proId)->orderBy('id','desc')->get();

            if(count($data)>0)
            {
            	$catelist = [];
	            foreach ($data as $key => $value) 
	            {   
	              	$catedata['category_id'] = $value->id;
		            $catedata['category_name'] = $value->cat_name; 

		            $catelist[] = $catedata;
	            } 
	              
	             return response()->json(['status'=>1,'message' =>'success.','result' => $catelist],200);

            }
            else{
            	return response()->json(['status'=>0,'message' =>'No data found','result' => []],
	        		config('global.success_status'));
            }

            
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            } 

    }

    /**
     * This is for show category list. 
     * @param  \App\Product  $subcategory 
     * @return \Illuminate\Http\Response
    */
    public function getSubCategoryList($cateId)
    {
    	try{         
            $data = ProductSubCategory::where('cat_id',$cateId)->orderBy('id','desc')->get();

            // dd($data);

            if(count($data)>0)
            {
            	$subcatelist = [];
	            foreach ($data as $key => $value) 
	            {   
	              	$subcatedata['sub_category_id'] = $value->id;
		            $subcatedata['sub_category_name'] = $value->sub_cat_name;

		            $proSize = $value->pro_size;

		            $subcatedata['product_size'] = explode(",",$proSize);

		            $subcatelist[] = $subcatedata;
	            } 
	              
	            return response()->json(['status'=>1,'message' =>'success.','result' => $subcatelist],200);

            }
            else{
            	return response()->json(['status'=>0,'message' =>'No data found','result' => []],
	        		config('global.success_status'));
            } 
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            } 

    }

    /**
     * This is for store Basic Price(per MT) according to product. 
     * @param  \App\Product  $subcategory 
     * @return \Illuminate\Http\Response
    */
    public function storePrice(Request $request)
    {
    	// dd($request->all());

    	\DB::beginTransaction();

   		try{

   			$validator = Validator::make($request->all(), [
                'pro_id'        => 'required', 
                'cat_id'     => 'required',
                'sub_cat_id'     => 'required',
                'size'     => 'required', 
                'basic_price' => ['required','regex:/^\d+(((,\d+)?,\d+)?,\d+)?$/'], 
	        ]);

	        if ($validator->fails()) { 
	            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $validator->errors()],config('global.failed_status'));
	        }

	        $input['pro_id'] = $request->pro_id;
    	   	$input['cat_id'] = $request->cat_id;
    	   	$input['sub_cat_id'] = $request->sub_cat_id;
    	   	$input['basic_price'] = $request->basic_price;
    	   	$input['size'] = $request->size;
    	   	$input['status'] = $request->status;

    	   	// dd($input);

    	   	$pricedata = PriceManagement::create($input);

    	   	\DB::commit();

    	   	if($pricedata)
                {
		            return response()->json(['status'=>1,'message' =>'Product base price successfully','result' => $pricedata],config('global.success_status'));
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
     * This is for price list.
     *
     * @param  Http\Controllers\Api\Modules\Freight  $product
     * @return \Illuminate\Http\Response
    */
	public function getPrice(Request $request)
	{
		\DB::beginTransaction();

      	try{

      		if($request->product_name && $request->status)
      		{
      			 
      			$data = DB::table('price_management')
      					->leftjoin('products','price_management.pro_id','products.id')
      					->leftjoin('categorys','price_management.cat_id','categorys.id')
      					->leftjoin('sub_categorys','price_management.sub_cat_id','sub_categorys.id') 
      					->select('price_management.id as price_id',
      							'price_management.size as size',
      							'price_management.basic_price as basic_price',
      							'price_management.status as status',
      							'products.id as product_id',
      							'products.pro_name as product_title',
      							'categorys.id as category_id',
      							'categorys.cat_name as category_name',
      							'sub_categorys.id as sub_category_id',
      							'sub_categorys.sub_cat_name as sub_category_name',
      							)
      					->where('products.pro_name',$request->product_name)
      					->where('price_management.status',$request->status) 
      					->get(); 
      					 
      		}
      		else if($request->product_name)
      		{
      			$data = DB::table('price_management')
      					->leftjoin('products','price_management.pro_id','products.id')
      					->leftjoin('categorys','price_management.cat_id','categorys.id')
      					->leftjoin('sub_categorys','price_management.sub_cat_id','sub_categorys.id') 
      					->select('price_management.id as price_id',
      							'price_management.size as size',
      							'price_management.basic_price as basic_price',
      							'price_management.status as status',
      							'products.id as product_id',
      							'products.pro_name as product_title',
      							'categorys.id as category_id',
      							'categorys.cat_name as category_name',
      							'sub_categorys.id as sub_category_id',
      							'sub_categorys.sub_cat_name as sub_category_name',
      							)
      					->where('products.pro_name',$request->product_name) 
      					->get(); 

      		}
      		else if($request->status)
      		{
      			$data = DB::table('price_management')
      					->leftjoin('products','price_management.pro_id','products.id')
      					->leftjoin('categorys','price_management.cat_id','categorys.id')
      					->leftjoin('sub_categorys','price_management.sub_cat_id','sub_categorys.id')  
      					->select('price_management.id as price_id',
      							'price_management.size as size',
      							'price_management.basic_price as basic_price',
      							'price_management.status as status',
      							'products.id as product_id',
      							'products.pro_name as product_title',
      							'categorys.id as category_id',
      							'categorys.cat_name as category_name',
      							'sub_categorys.id as sub_category_id',
      							'sub_categorys.sub_cat_name as sub_category_name',
      							)
      					->where('price_management.status',$request->status) 
      					->get(); 

      		}
      		else
      		{
      			$data = DB::table('price_management')
      					->leftjoin('products','price_management.pro_id','products.id')
      					->leftjoin('categorys','price_management.cat_id','categorys.id')
      					->leftjoin('sub_categorys','price_management.sub_cat_id','sub_categorys.id')
      					->select('price_management.id as price_id',
      							'price_management.size as size',
      							'price_management.basic_price as basic_price',
      							'price_management.status as status',
      							'products.id as product_id',
      							'products.pro_name as product_title',
      							'categorys.id as category_id',
      							'categorys.cat_name as category_name',
      							'sub_categorys.id as sub_category_id',
      							'sub_categorys.sub_cat_name as sub_category_name',
      							)
      					->get(); 
      		}

      		
      		// dd($data);  

            \DB::commit(); 

            if(!empty($data))
            {      
                
                 
	            return response()->json(['status'=>1,'message' =>config('global.sucess_msg'),
	            	'result' => $data],config('global.success_status'));
	        }
	        else{ 
	         	 return response()->json(['status'=>1,'message' =>'No data found','result' => []],
	        		config('global.success_status'));
	        }


       	}catch(\Exception $e){ 
        	\DB::rollback(); 
            return response()->json(['status'=>0,'message' =>config('global.failed_msg'),'result' => $e->getMessage()],config('global.failed_status'));
      	}
	}
}
