	<?php

	namespace App\Http\Controllers\Modules\Api\Category;

	use Illuminate\Http\Request;
	use App\Http\Controllers\Controller;
	use Tymon\JWTAuth\Exceptions\JWTException;
	use Illuminate\Support\Facades\Auth;
	use App\User;
	use App\Models\Category;
	use App\Models\ProductSubCategory;
	use JWTAuth;
	use Validator;


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
	   		if (@$request->cat_id) 
	        {

	        	$validation = \Validator::make($request->all(),[ 
	                "cat_name" => "required|max:200|unique:categorys,cat_name,".$request->cat_id,
	                "cat_id" => "required|numeric",
	                "cat_dese" => "required|max:200", 
	            ],[ 'cat_name.required'=>'Category name is required.',
	            	'cat_name.unique'=>'Category already exists.',
	                'cat_dese.required'=>'Category description required.',
	                'cat_id.required'=>'Category id required.', 
	            ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

		        $updatecat['cat_name'] = $request->cat_name;
		        $updatecat['cat_dese'] = $request->cat_dese;

		        $categoryData = Category::where('id',$request->cat_id)->update($updatecat); 

		        $catData = Category::where('id',$request->cat_id)->first();

		        // return response()->json(['sucs'=>'New category added successfully.'],200);

		   	  	return response()->json(['status'=>1,'message' =>'Category updated successfully.','result' => $catData],200);


	        }
	        else
	        {
	        	$validation = \Validator::make($request->all(),[ 
	                "cat_name" => "required|unique:categorys|max:200",
	                "cat_dese" => "required|max:200", 
	            ],[ 'cat_name.required'=>'Category name is required.',
	            	'cat_name.unique'=>'Category already exists.',
	                'cat_dese.required'=>'Category description required.', 
	            ]);

		        if ($validation->fails()) {
		            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
		        }

		        $input['cat_name'] = $request->cat_name;
		        $input['cat_dese'] = $request->cat_dese;

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
	         $response['success'] = true;
	         $response['data'] = $data;  
	         return response()->json(['status'=>1,'message' =>'success.','result' => $data],200);
	         // return response()->json(['status'=>1,$response],200);
	          
	        
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
	   			return response()->json(['status'=>1,'message' =>'success','result' => $catData],200);
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
