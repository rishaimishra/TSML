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
   	public function indexPage(Request $request)
   	{
   		dd('index page');
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

        		$validation = \Validator::make($request->all(),[ 
	            "cat_id" => "required|numeric",
	            "sub_cat_id" => "required|numeric",
	            "pro_name" => "required|max:200", 
	            "pro_desc" => "required|max:200",
	            "pro_size" => "required|max:200",  
		        ],[ 'cat_id.required'=>'Category id required.',
		        	'sub_cat_id.required'=>'Sub category id required.',
		        	'pro_name.required'=>'Product name required.',
		            'pro_size.required'=>'Product size required.', 
		        ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

			   	$input['cat_id'] = $request->cat_id;
				$input['sub_cat_id'] = $request->sub_cat_id;
				$input['pro_name'] = $request->pro_name;
				$input['pro_desc'] = $request->pro_desc;
				$input['Cr'] = $request->Cr;
				$input['C'] = $request->C;
				$input['Phos'] = $request->Phos;
				$input['S'] = $request->S;
				$input['Si'] = $request->Si;
				 
				if ($request->hasFile('primary_image'))
			    {
			    	 
			    	@unlink(storage_path('app/public/images/product/'.$chkpro->primary_image)); 

			    	$image = $request->primary_image;
	                $filename = $request->pro_name.'-'.time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
	                Storage::putFileAs('public/images/product/', $image, $filename);
	                $input['primary_image'] = $filename;
			    	 
			    }
			    if ($request->hasFile('image_2'))
			    {
			    	@unlink(storage_path('app/public/images/product/'.$chkpro->image_2)); 

			    	$image = $request->image_2;
	                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
	                Storage::putFileAs('public/images/product/', $image, $filename);
	                $input['image_2'] = $filename;

			    	 
			    }
			    if ($request->hasFile('image_3'))
			    {	
			    	@unlink(storage_path('app/public/images/product/'.$chkpro->image_3)); 

			    	$image = $request->image_3;
	                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
	                Storage::putFileAs('public/images/product/', $image, $filename);
	                $input['image_3'] = $filename; 
			    	 
			    }
			    if ($request->hasFile('image_4'))
			    {
			    	@unlink(storage_path('app/public/images/product/'.$chkpro->image_4)); 

			    	$image = $request->image_4;
	                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
	                Storage::putFileAs('public/images/product/', $image, $filename);
	                $input['image_4'] = $filename; 
 
			    }
			     
			    $input['pro_size'] = implode(",",$request->pro_size);
				// foreach($pro_size as $key) {
				//     $input['pro_size']= array('name' => 'test_name', 'test' => $key, 'property' => 'test_property');
				//     // Test_table::insert($data);    
				// } 

			    // dd($input);


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
	            "cat_id" => "required|numeric",
	            "sub_cat_id" => "required|numeric",
	            "pro_name" => "required|max:200", 
	            "pro_desc" => "required|max:200",
	            "pro_size" => "required|max:200",  
	        ],[ 'cat_id.required'=>'Category id required.',
	        	'sub_cat_id.required'=>'Sub category id required.',
	        	'pro_name.required'=>'Product name required.',
	            'pro_size.required'=>'Product size required.', 
	        ]);

	        if ($validation->fails()) {
	            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
	        }

		   	$input['cat_id'] = $request->cat_id;
			$input['sub_cat_id'] = $request->sub_cat_id;
			$input['pro_name'] = $request->pro_name;
			$input['pro_desc'] = $request->pro_desc;
			$input['Cr'] = $request->Cr;
			$input['C'] = $request->C;
			$input['Phos'] = $request->Phos;
			$input['S'] = $request->S;
			$input['Si'] = $request->Si;
		
			if ($request->hasFile('primary_image'))
		    {  

		    	$image = $request->primary_image; 

                $filename = $request->pro_name.'-'.time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
                Storage::putFileAs('public/images/product/', $image, $filename);

                $input['primary_image'] = $filename;

		    }
		    if ($request->hasFile('image_2'))
		    {
		    	$image = $request->image_2; 

                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
                Storage::putFileAs('public/images/product/', $image, $filename);

                $input['image_2'] = $filename;

		    	 
		    }
		    if ($request->hasFile('image_3'))
		    {
		    	$image = $request->image_3; 

                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
                Storage::putFileAs('public/images/product/', $image, $filename);

                $input['image_3'] = $filename;

		    	 
		    }
		    if ($request->hasFile('image_4'))
		    {
		    	$image = $request->image_4; 

                $filename = time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
                Storage::putFileAs('public/images/product/', $image, $filename);

                $input['image_4'] = $filename; 
		    	 
		    }
		     
		    $input['pro_size'] = implode(",",$request->pro_size);
			// foreach($pro_size as $key) {
			//     $input['pro_size']= array('name' => 'test_name', 'test' => $key, 'property' => 'test_property');
			//     // Test_table::insert($data);    
			// } 

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
	   		$proData['category_id'] = $getProdct->cat_id;
	   		$proData['sub_category_id'] = $getProdct->sub_cat_id;
	   		$proData['product_name'] = $getProdct->pro_name;
	   		$proData['product_desc'] = $getProdct->pro_desc;
	   		$proData['cr'] = $getProdct->Cr;
	   		$proData['c'] = $getProdct->C;
	   		$proData['phos'] = $getProdct->Phos;
	   		$proData['s'] = $getProdct->S;
	   		$proData['si'] = $getProdct->Si;
	   		$proData['pro_size'] = $getProdct->pro_size;
	   		$proData['is_delete'] = $getProdct->is_delete;
	   		$proData['status'] = $getProdct->status;
	   		if ($getProdct->primary_image) 
	   		{

	   			$proData['primary_image_url'] = asset('storage/app/public/images/product/'.$getProdct->primary_image);
	   		}
	   		else
	   		{
	   			$proData['primary_image_url'] =  null;
	   		}
	   		
	   		if($getProdct->image_2)
	   		{
	   			$proData['image_2_url'] =  asset('storage/app/public/images/product/'.$getProdct->image_2);
	   		}
	   		else
	   		{
	   			$proData['image_2_url'] =  null;	
	   		}

	   		if($getProdct->image_3)
	   		{
	   			$proData['image_3_url'] =  asset('storage/app/public/images/product/'.$getProdct->image_3);
	   		}
	   		else
	   		{
	   			$proData['image_3_url'] =  null;	
	   		}

	   		if($getProdct->image_4)
	   		{
	   			$proData['image_4_url'] =  asset('storage/app/public/images/product/'.$getProdct->image_4);
	   		}
	   		else
	   		{
	   			$proData['image_4_url'] =  null;	
	   		} 

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
            $data = Product::where('is_delete','!=',1)->orderBy('id','desc')->get();

            $prolist = [];
            foreach ($data as $key => $value) 
            {  
	            
	            $prodata['category_id'] = $value->getCateDetails->id;
              	$prodata['category_name'] = $value->getCateDetails->cat_name;
              	$prodata['sub_category_id'] = $value->sub_cat_id;
              	$prodata['sub_category_name'] = $value->getSubCateDetails->sub_cat_name;
              	$prodata['product_id'] = $value->id;
	            $prodata['product_title'] = $value->pro_name;
	            $prodata['product_status'] = $value->status;

	            if ($value->primary_image) 
		   		{

		   			$prodata['primary_image_url'] = asset('storage/app/public/images/product/'.$value->primary_image);
		   		}
		   		else
		   		{
		   			$prodata['primary_image_url'] =  null;
		   		}

	              
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
