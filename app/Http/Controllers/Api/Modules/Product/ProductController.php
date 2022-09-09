<?php

namespace App\Http\Controllers\Api\Modules\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSubCategory;
use JWTAuth;
use Validator;
use Response;
use File; 
use Storage; 


class ProductController extends Controller
{
    /**
     * This is for display product in index page.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function productManu(Request $request)
    {
        $getproduct = Product::where('status','=',1)->get(); //1=>Active product

        if (!empty($getproduct)) 
        {
           return response()->json(['status'=>1,'message' =>'Success.','result' => $getproduct],200);
        }
        else
        {
            return response()->json(['status'=>1,'message' =>'No produ found.','result' => $getproduct],200);
        }
    }
	/**
     * This is for display product in index page.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
   	public function indexPage($proId)
   	{
   		// dd('index page');
        $chkpro = Product::where('id',$proId)->first();
        if (!empty($chkpro)) 
        {
             
            $data = Category::where('product_id',$proId)->where('status','!=',2)->orderBy('id','desc')->get();
            
            if (count($data) > 0) 
            {
                $catelist = [];
                foreach ($data as $key => $value) 
                {  
                    
                    $catdata['category_id'] = $value->id;
                    $catdata['category_name'] = $value->cat_name;
                    $catdata['is_populer'] = $value->is_populer;  
                    $catdata['product_id'] = $value->product_id;
                    $catdata['product_title'] = $value->getProductDetails->pro_name;
                    $catdata['product_slug'] = $value->getProductDetails->slug;
                    

                    if ($value->primary_image) 
                    {

                        $catdata['primary_image_url'] = asset('storage/app/public/images/product/'.$value->primary_image);
                    }
                    else
                    {
                        $catdata['primary_image_url'] =  null;
                    }

                      
                    $catelist[] = $catdata;
                } 
                return response()->json(['status'=>1,'message' =>'success.','result' => $catelist],200);
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'No product found'],200);
            }  


            
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No product found'],200);
        }
        
   	}

    /**
     * This is for store new product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
   public function storeProduct(Request $request)
   {
   	 // dd($request->all());
   		if (@$request->pro_id) 
        {
        	// For update product.....
        	$chkpro = Product::find($request->pro_id);

        	if (!empty($chkpro)) {

        		// For insert new product.....
		   		$validation = \Validator::make($request->all(),[  
		   			"pro_id" => "required|numeric",
		            "pro_name" => "required|max:200", 
		            "pro_desc" => "required|max:200",
		               
		        ],[ 
		        	'pro_id.required'=>'Product id is required.', 
		        	'pro_name.required'=>'Product name is required.',
		            'pro_desc.required'=>'Product description is required.', 
		        ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

			   	 
				$input['pro_name'] = $request->pro_name;
				$input['pro_desc'] = $request->pro_desc; 
				$input['slug'] = str_slug($request->pro_name);
				 
				 

				$productData = Product::where('id',$chkpro->id)->update($input); 
				$getProData = Product::where('id',$chkpro->id)->first();
			     

				return response()->json(['status'=>1,'message' =>'Product updated successfully.','result' => $getProData],200);
        		 
        	}
        	else{
        		return response()->json(['status'=>0,'message'=>'No data found'],200);
        	}
        	
        } // End for update product.....
        else
        {
        	// For insert new product.....
	   		$validation = \Validator::make($request->all(),[  
	            "pro_name" => "required|max:200", 
	            "pro_desc" => "required|max:200",
	               
	        ],[  
	        	'pro_name.required'=>'Product name is required.',
	            'pro_desc.required'=>'Product description is required.', 
	        ]);

	        if ($validation->fails()) {
	            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
	        }

		   	 
			$input['pro_name'] = $request->pro_name;
			$input['pro_desc'] = $request->pro_desc; 
			$input['slug'] = str_slug($request->pro_name);
			
			// dd($input);

			$productData = Product::create($input); 

		    // $response['success']['message']="New product added successfully.";
		    //         return Response::json($response);

			return response()->json(['status'=>1,'message' =>'New product added successfully.','result' => $productData],200);
		} // End for insert new product.....
   }

   /**
     * This is for show product details before edit.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
   	public function editProduct($proId)
   	{
   		 
   		$getProdct = Product::find($proId); 
   		 
   		if (!empty($getProdct)) 
   		{
   			$proData['id'] = $getProdct->id; 
	   		$proData['product_name'] = $getProdct->pro_name;
	   		$proData['product_desc'] = $getProdct->pro_desc;
	   		$proData['slug'] = $getProdct->slug; 
	   		$proData['status'] = $getProdct->status; 
   			return response()->json(['status'=>1,'message' =>'success','result' => $proData],200);
   		}
   		else{
   			return response()->json(['status'=>0,'message'=>'No data found'],200);
   		}
   	}

   	/**
     * This is for delete product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct($id)
    {  
        $product = Product::where('id',$id)->first();  

        if(!empty($product))
        { 
        	$input['is_delete'] = 1; //1=>Delete. 

            $updateuser = Product::where('id',$product->id)->update($input);
            return response()->json(['status'=>1,'message' =>'Product delete successfully.'],200);
      
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
        
    }

    /**
     * This is for show product list. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function productList(Request $request)
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
     * This is for active product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function activeProduct($id)
    {  
        $getProduct = Product::where('id',$id)->first();  

        if(!empty($getProduct))
        { 
    		$input['status'] = 1; //2=> Inactive/1=>Active. 

        	$updateuser = Product::where('id',$getProduct->id)->update($input);

 
        	return response()->json(['status'=>1,'message' =>'Product status active successfully.']);
        	 
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
        
    }

    /**
     * This is for inactive product.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function inactiveProduct($id)
    {  
        $getProduct = Product::where('id',$id)->first();  

        if(!empty($getProduct))
        { 
    		$input['status'] = 2; //2=> Inactive/1=>Active. 

        	$updateuser = Product::where('id',$getProduct->id)->update($input);
 
        	return response()->json(['status'=>1,'message' =>'Product status inactive successfully.']);        	 
        }
        else
        {
            return response()->json(['status'=>0,'message'=>'No data found'],200);
        }
        
    }

    /**
     * This is for show product list with search. 
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
    */
    public function productListMy(Request $request)
    { 
        $limit = $request->perPage_count;
        $page = $request->current_page;
         

        $offsatval = ($page-1)*$limit;

        $chkproduct = Product::get();

        
        if(count($chkproduct)>0)
        {
        	if(!empty($offsatval || $limit) )
	        {
	        	$getsubcategory = Product::orderBy('id','DESC')
                            ->with('getCateDetails')
                            ->with('getSubCateDetails')
                            ->offset($offsatval)
	                        ->limit($limit)
                            ->get();
	        }
	        else
	        {
	        	$getsubcategory = Product::orderBy('id','DESC')
                                            ->with('getCateDetails')
                                            ->with('getSubCateDetails')
                                            ->get();
	        }

	        if(!empty($request->search_keyword) )
	    		{
	    			if (isset($request->search_keyword)) 
			            {
			            	$search = $request->search_keyword;
			             

			            	$getsubcategory = Product::whereHas('getCateDetails', function ($query) use ($search) {
                                    $query->where('cat_name','LIKE',"%{$search}%");
                                })->orWhereHas('getSubCateDetails', function ($query) use ($search)
			            			{
                                    	$query->where('sub_cat_name','LIKE',"%{$search}%");
                                	})->orWhere('pro_name','LIKE',"%{$search}%");

                                if(!empty($offsatval || $limit) ){
                                	$getsubcategory
                                    ->orWhere('pro_name','LIKE',"%{$search}%")
                                    ->offset($offsatval)
                                    ->limit($limit);
                                }
                                
                               $getsubcategory = $getsubcategory->get();


			            }
	    		}

	    	 
	    	$totalData = Product::count();

	    	$getsubcategorylist = [];

	    	if($getsubcategory!=null)
	    	{	

	    		foreach ($getsubcategory as $key => $value) { 
	    		 	$getsubcategorylist[]= array(
	    		 		'category_id' => $value->getCateDetails->id,
		              	'category_name' => $value->getCateDetails->cat_name,
		              	'sub_category_id' => $value->sub_cat_id,
		              	'sub_category_name' => $value->getSubCateDetails->sub_cat_name, 
	    		 		'product_id' => $value->id,
	           			'product_title' => $value->pro_name,
	             		'product_status' => $value->status
	    		 	);
	    		}
 

	    		return response()->json(['status'=>1,'message'=>'success','data' =>array('total_data' => $totalData,'perPage_count'=>$limit,'current_page'=>$page,'sub_category_list'=>$getsubcategorylist )], 200);
	    	}
	    	else
	    	{
	    		 
	    		return response()->json(['status'=>0,'message' => 'Something went wrong try again latter']);
	    	} 
        }
        else
        {
        	return response()->json(['status'=>0,'message' => 'No data found']); 
        } 
    }
}
