<?php

	namespace App\Http\Controllers\Api\Modules\Category;

	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Tymon\JWTAuth\Exceptions\JWTException;
	use Illuminate\Support\Facades\Auth;
	use App\User;
	use App\Models\Category;
	use App\Models\ProductSubCategory;
	use JWTAuth;
	use Validator;
	use File; 
	use Storage;


	class CategoryController extends Controller
	{
	    /**
	     * This is for store new Category.
	     *
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	     */
	   public function storeCategory(Request $request)
	   {
	   	// dd($request->cat_id);
	   		if (@$request->cat_id) 
	        {

	        	$validation = \Validator::make($request->all(),[ 
	        		"product_id" => "required|numeric",
	                "cat_name" => 'required|max:200|unique:categorys,cat_name,'.$request->cat_id,
	                "cat_id" => "required|numeric",
	                "cat_dese" => "required|max:200", 
	            ],[ 'product_id.required'=>'Product id is required.',
	            	'cat_name.required'=>'Category name is required.',
	            	'cat_name.unique'=>'Category already exists.',
	                'cat_dese.required'=>'Category description required.',
	                'cat_id.required'=>'Category id required.', 
	            ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

		        $updatecat['product_id'] = $request->product_id;
		        $updatecat['cat_name'] = $request->cat_name;
		        $updatecat['cat_dese'] = $request->cat_dese;
		        $updatecat['slug'] = str_slug($request->cat_name);

		         

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

		        $categoryData = Category::where('id',$request->cat_id)->update($updatecat); 

		        $catData = Category::where('id',$request->cat_id)->first();

		        // return response()->json(['sucs'=>'New category added successfully.'],200);

		   	  	return response()->json(['status'=>1,'message' =>'Category updated successfully.','result' => $catData],200);


	        }
	        else
	        {
	        	$validation = \Validator::make($request->all(),[ 
	        		"product_id" => "required|numeric",
	                "cat_name" => "required|unique:categorys|max:200",
	                "cat_dese" => "required|max:200", 
	            ],[ 'product_id.required'=>'Product id is required.',
	            	'cat_name.required'=>'Category name is required.',
	            	'cat_name.unique'=>'Category already exists.',
	                'cat_dese.required'=>'Category description required.', 
	            ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

		        $input['product_id'] = $request->product_id;
		        $input['cat_name'] = $request->cat_name;
		        $input['cat_dese'] = $request->cat_dese;
		        $input['slug'] = str_slug($request->cat_name);

		        if ($request->hasFile('primary_image'))
			    {  

			    	$image = $request->primary_image; 

	                $filename = $input['slug'].'-'.time().'-'.rand(1000,9999).'.'.$image->getClientOriginalExtension();
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

		        $categoryData = Category::create($input); 

		        // return response()->json(['sucs'=>'New category added successfully.'],200);

		   	  	return response()->json(['status'=>1,'message' =>'New category added successfully.','result' => $categoryData],200);
			}
	    }

	    /**
	     * This is for show category list. 
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	    */
	    public function categoryList(Request $request)
	    {
	    	 
	        $response = [];
	        try{         
	        $data = Category::where('status','!=',2)->orderBy('id','desc')->get();

	        $catelist = [];
	            foreach ($data as $key => $value) 
	            {  
		            
		            $catdata['category_id'] = $value->id;
	              	$catdata['category_name'] = $value->cat_name; 
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
	          
	        
	        }catch(\Exception $e){
	            $response['error'] = $e->getMessage();
	            return response()->json([$response]);
	        }
	    }
	   		

	   	/**
	     * This is for show category details before edit.
	     *
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	    */
	   	public function editCategory($catId)
	   	{
	   		 
	   		$catData = Category::find($catId);

	   		if (!empty($catData)) 
	   		{
	   			  
		            
	           	$catdetails['category_id'] = $catData->id;
              	$catdetails['category_name'] = $catData->cat_name; 
              	$catdetails['product_id'] = $catData->product_id;
	            $catdetails['product_title'] = $catData->getProductDetails->pro_name;
	            $catdetails['product_slug'] = $catData->getProductDetails->slug;

		   		if ($catData->primary_image) 
		   		{

		   			$catdetails['primary_image_url'] = asset('storage/app/public/images/product/'.$catData->primary_image);
		   		}
		   		else
		   		{
		   			$catdetails['primary_image_url'] =  null;
		   		}
		   		
		   		if($catData->image_2)
		   		{
		   			$catdetails['image_2_url'] =  asset('storage/app/public/images/product/'.$catData->image_2);
		   		}
		   		else
		   		{
		   			$catdetails['image_2_url'] =  null;	
		   		}

		   		if($catData->image_3)
		   		{
		   			$catdetails['image_3_url'] =  asset('storage/app/public/images/product/'.$catData->image_3);
		   		}
		   		else
		   		{
		   			$catdetails['image_3_url'] =  null;	
		   		}

		   		if($catData->image_4)
		   		{
		   			$catdetails['image_4_url'] =  asset('storage/app/public/images/product/'.$catData->image_4);
		   		}
		   		else
		   		{
		   			$catdetails['image_4_url'] =  null;	
		   		} 

	   			return response()->json(['status'=>1,'message' =>'success','result' => $catdetails],200); 
 
	   		}
	   		else{
	   			return response()->json(['status'=>0,'message'=>'No data found'],200);
	   		}
	   	}

	   	/**
	     * This is for inactive category.
	     *
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	     */
	    public function inactiveCategory($id)
	    {  
	        $category = Category::where('id',$id)->first();  

	        if(!empty($category))
	        {
	        	$checkForUse = ProductSubCategory::where('cat_id',$category->id)->get();

	        	 
	        	if (!empty($checkForUse)) {
	        		$input['status'] = 2; //2=> Inactive/1=>Active. 

	            	$updateuser = Category::where('id',$category->id)->update($input);

	     
	            	return response()->json(['status'=>1,'message' =>'Category status inactive successfully.']);
	        	}
	        	else{
	        		return response()->json(['status'=>1,'message' =>'You can not inactive this category it is in use !!!']);        		 
	        	} 
	        }
	        else
	        {
	            return response()->json(['status'=>0,'message'=>'No data found'],200);
	        }
	        
	    }

	    /**
	     * This is for active category.
	     *
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	     */
	    public function activeCategory($id)
	    {  
	        $category = Category::where('id',$id)->first();  

	        if(!empty($category))
	        {
	        	$input['status'] = 1; //2=> Inactive/1=>Active. 

	            $updateuser = Category::where('id',$category->id)->update($input);
	     
	            return response()->json(['status'=>1,'message' =>'Category status active successfully.']); 
	        }
	        else
	        {
	            return response()->json(['status'=>0,'message'=>'No data found'],200);
	        }
	        
	    }

	    /**
	     * This is for show category list. 
	     * @param  \App\Product  $product
	     * @return \Illuminate\Http\Response
	    */
	    public function categoryListMy(Request $request)
	    {


	        $limit = $request->perPage_count;
	        $page = $request->current_page;
	         

	        $offsatval = ($page-1)*$limit;

	        $chkproduct = Category::get();

	        
	        if(count($chkproduct)>0)
	        {
	        	 if(!empty($offsatval || $limit) )
		        {
		        	$getpayment = Category::orderBy('id','DESC')->offset($offsatval)
		                            ->limit($limit)->get();
		        }
		        else
		        {
		        	$getpayment = Category::orderBy('id','DESC')->get();
		        }

		        if(!empty($request->search_keyword) )
		    		{
		    			if (isset($request->search_keyword)) 
				            {

				            	$getpayment = Category::where(function($q) use($request){
					                   $q->where('cat_name',$request->search_keyword);
					                });
				            		 
				            		if(!empty($offsatval || $limit) ){
				            			$getpayment->offset($offsatval)
				            						->limit($limit);
				            		}
				            		
				            		$getpayment = $getpayment->get();

				            }
		    		}

		    	 
		    	$totalData = Category::count();

		    	$getpaymentlist = [];

		    	if($getpayment!=null)
		    	{	

		    		foreach ($getpayment as $key => $value) { 
		    		 	$getpaymentlist[]= array('cat_name'=>$value->cat_name,'cat_dese'=>$value->cat_dese,'status'=>$value->status);
		    		}


		    		return response()->json(['status'=>1,'message'=>'success','data' =>array('total_data' => $totalData,'perPage_count'=>$limit,'current_page'=>$page,'category_list'=>$getpaymentlist )], 200);
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
