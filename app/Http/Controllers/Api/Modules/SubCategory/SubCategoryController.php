<?php

  namespace App\Http\Controllers\Api\Modules\SubCategory;

  use Illuminate\Http\Request;
  use App\Http\Controllers\Controller;
  use Tymon\JWTAuth\Exceptions\JWTException;
  use Illuminate\Support\Facades\Auth;
  use App\User;
  use App\Models\Category;
  use App\Models\ProductSubCategory;
  use JWTAuth;
  use Validator;

    class SubCategoryController extends Controller
    {
    	/**
         * This is for store new Sub Category.
         *
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
         */
       public function storeSubCategory(Request $request)
       {
       		$cataId = $request->cat_id;
       		$chkcata = Category::where('id',$cataId)->first();

       		if (@$request->sub_cat_id) 
    	    {  	

    	   		$validation = \Validator::make($request->all(),[ 
                    "cat_id" => "required|numeric",
                    "pro_id" => "required|numeric",    	   			 
    	   			"sub_cat_id" => "required|numeric",
    	            "sub_cat_name" => "required|max:200|unique:sub_categorys,sub_cat_name,".$request->sub_cat_id,
    	            "sub_cat_dese" => "required|max:200", 
                    "pro_size" => "required|max:200",
    	        ],[ 
    	        	'cat_id.required'=>'Category id is required.',
                    'pro_id.required'=>'Product id is required.',
    	        	'sub_cat_id.required'=>'Sub category id is required.',
    	        	'sub_cat_name.required'=>'Sub category name is required.',
    	        	'sub_cat_name.unique'=>'Sub category already exists.',
    	            'sub_cat_dese.required'=>'Sub category description required.', 
                    'pro_size.required'=>'Product size required.',
    	        ]);

    	        if ($validation->fails()) {
    	            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
    	        }

    	   		
    	   		 
    	   		if (!empty($chkcata)) {
    	   			 
                    $updateSubCat['cat_id'] = $request->cat_id;
                    $updateSubCat['pro_id'] = $request->pro_id;
                    $updateSubCat['sub_cat_name'] = $request->sub_cat_name;
                    $updateSubCat['slug'] = str_slug($request->sub_cat_name);
                    $updateSubCat['sub_cat_dese'] = $request->sub_cat_dese;
                    $updateSubCat['pro_size'] = implode(",",$request->pro_size);
                    $updateSubCat['Cr'] = $request->Cr;
                    $updateSubCat['C'] = $request->C;
                    $updateSubCat['Phos'] = $request->Phos;
                    $updateSubCat['S'] = $request->S;
                    $updateSubCat['Si'] = $request->Si;

    		        $subCategoryData = ProductSubCategory::where('id',$request->sub_cat_id)->update($updateSubCat); 
    		        $subCatData = ProductSubCategory::where('id',$request->sub_cat_id)->first();	         

    		   	  	return response()->json(['status'=>1,'message' =>'Sub category update successfully.','result' => $subCatData],200);
    	   			 
    	   		}
    	   		else{
    	   			return response()->json(['status'=>0,'message'=>'No category found'],200);
    	   		}
    	    }
       		// dd($request->all());
       		else
       		{
       			$validation = \Validator::make($request->all(),[ 
                "cat_id" => "required|numeric",
       			"pro_id" => "required|numeric",
                "sub_cat_name" => "required|unique:sub_categorys|max:200",
                "sub_cat_dese" => "required|max:200", 
                "pro_size" => "required|max:200",
    	        ],[ 
    	        	'cat_id.required'=>'Category id is required.',
                    'pro_id.required'=>'Product id is required.',
    	        	'sub_cat_name.required'=>'Sub category name is required.',
    	        	'sub_cat_name.unique'=>'Sub category already exists.',
    	            'sub_cat_dese.required'=>'Sub category description required.',
                    'pro_size.required'=>'Product size required.',  
    	        ]);

    	        if ($validation->fails()) {
    	            return response()->json(['status'=>0,'errors'=>$validation->errors()],200);
    	        }   	 
    	   		 
    	   		if (!empty($chkcata)) {
                    $input['cat_id'] = $request->cat_id;
    	   			$input['pro_id'] = $request->pro_id;
    	   			$input['sub_cat_name'] = $request->sub_cat_name;
                    $input['slug'] = str_slug($request->sub_cat_name);
    		        $input['sub_cat_dese'] = $request->sub_cat_dese;
                    $input['pro_size'] = implode(",",$request->pro_size);
                    $input['Cr'] = $request->Cr;
                    $input['C'] = $request->C;
                    $input['Phos'] = $request->Phos;
                    $input['S'] = $request->S;
                    $input['Si'] = $request->Si;

    		        $subCategoryData = ProductSubCategory::create($input); 	         

    		   	  	return response()->json(['status'=>1,'message' =>'New sub category added successfully.','result' => $subCategoryData],200);
    	   			 
    	   		}
    	   		else{
    	   			return response()->json(['status'=>0,'message'=>'No category found'],200);
    	   		}
       		}

       		
       }

       /**
         * This is for show sub category list. 
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
        */
        public function subCategoryList(Request $request)
        {
            
            try{         
             $data = ProductSubCategory::where('status','!=',2)->with('getCategoryDetails')->orderBy('id','desc')->get();

             $subcatdata = [];
             foreach ($data as $key => $value) 
             {

              $catadata['sub_category_id'] = $value->id;
              $catadata['sub_category_name'] = $value->sub_cat_name;
              $catadata['sub_category_slug'] = $value->slug;
              $catadata['sub_category_desc'] = $value->sub_cat_dese;
              $catadata['sub_category_status'] = $value->status;
              $catadata['category_id'] = $value->getCategoryDetails->id;
              $catadata['category_name'] = $value->getCategoryDetails->cat_name;
              $catadata['product_id'] = $value->pro_id;
              $catadata['product_name'] = $value->getCategoryDetails->getProductDetails->pro_name;
              
              $subcatdata[] = $catadata;
             } 
              
             return response()->json(['status'=>1,'message' =>'success.','result' => $subcatdata],200);
             // return response()->json(['status'=>1,$response],200);
              
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return response()->json([$response]);
            }
        }

       	/**
         * This is for show sub category details before edit.
         *
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
        */
       	public function editSubCategory($subCatId)
       	{
       		 
       		$catData = ProductSubCategory::find($subCatId);

       		if (!empty($catData)) 
       		{
                $catadetails['sub_category_id'] = $catData->id;
                $catadetails['sub_category_name'] = $catData->sub_cat_name;
                $catadetails['sub_category_slug'] = $catData->slug;
                $catadetails['sub_category_desc'] = $catData->sub_cat_dese;
                $catadetails['sub_category_status'] = $catData->status;
                $catadetails['category_id'] = $catData->getCategoryDetails->id;
                $catadetails['category_name'] = $catData->getCategoryDetails->cat_name;
                $catadetails['product_id'] = $catData->pro_id;
                $catadetails['product_name'] = $catData->getCategoryDetails->getProductDetails->pro_name;

       			return response()->json(['status'=>1,'message' =>'success','result' => $catadetails],200);
       		}
       		else{
       			return response()->json(['status'=>0,'message'=>'No data found'],200);
       		}
       	}

       	/**
         * This is for inactive sub category.
         *
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
         */
        public function inactiveSubCategory($id)
        {  
            $subCategory = ProductSubCategory::where('id',$id)->first();  

            if(!empty($subCategory))
            { 
        		$input['status'] = 2; //2=> Inactive/1=>Active. 

            	$updateuser = ProductSubCategory::where('id',$subCategory->id)->update($input);

     
            	return response()->json(['status'=>1,'message' =>'Sub category status inactive successfully.']);
            	 
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'No data found'],200);
            }
            
        }

        /**
         * This is for active sub category.
         *
         * @param  \App\Product  $product
         * @return \Illuminate\Http\Response
         */
        public function activeSubCategory($id)
        {  
            $subCategory = ProductSubCategory::where('id',$id)->first();  

            if(!empty($subCategory))
            { 
        		$input['status'] = 1; //2=> Inactive/1=>Active. 

            	$updateuser = ProductSubCategory::where('id',$subCategory->id)->update($input);

     
            	return response()->json(['status'=>1,'message' =>'Sub category status active successfully.']);
            	 
            }
            else
            {
                return response()->json(['status'=>0,'message'=>'No data found'],200);
            }
            
        }

        

        /**
    	     * This is for show sub category list with search. 
    	     * @param  \App\Product  $product
    	     * @return \Illuminate\Http\Response
    	    */
    	    public function subCategoryListMy(Request $request)
    	    {


    	        $limit = $request->perPage_count;
    	        $page = $request->current_page;
    	         

    	        $offsatval = ($page-1)*$limit;

    	        $chkproduct = ProductSubCategory::get();

    	        
    	        if(count($chkproduct)>0)
    	        {
    	        	 if(!empty($offsatval || $limit) )
    		        {
    		        	$getsubcategory = ProductSubCategory::orderBy('id','DESC')
                                    ->with('getCategoryDetails')
                                    ->offset($offsatval)
    		                            ->limit($limit)
                                    ->get();
    		        }
    		        else
    		        {
    		        	$getsubcategory = ProductSubCategory::orderBy('id','DESC')
                                                        ->with('getCategoryDetails')
                                                        ->get();
    		        }

    		        if(!empty($request->search_keyword) )
    		    		{
    		    			if (isset($request->search_keyword)) 
    				            {
    				            	$search = $request->search_keyword;
    				             

    				            	$getsubcategory = ProductSubCategory::whereHas('getCategoryDetails', function ($query) use ($search) {
                                            $query->where('cat_name','LIKE',"%{$search}%");
                                        }) 
                                        ->orWhere('sub_cat_name','LIKE',"%{$search}%");

                                        if(!empty($offsatval || $limit) ){
                                          $getsubcategory
                                            ->orWhere('sub_cat_name','LIKE',"%{$search}%")
                                            ->offset($offsatval)
                                            ->limit($limit);
                                        }
                                        
                                       $getsubcategory = $getsubcategory->get();
     

    				            }
    		    		}

    		    	 
    		    	$totalData = ProductSubCategory::count();

    		    	$getsubcategorylist = [];

    		    	if($getsubcategory!=null)
    		    	{	

    		    		foreach ($getsubcategory as $key => $value) { 
    		    		 	$getsubcategorylist[]= array('sub_category_id'=>$value->id,'sub_category_name'=>$value->sub_cat_name,'sub_category_desc'=>$value->sub_cat_dese,'sub_category_status'=>$value->status,'category_id'=>$value->getCategoryDetails->id,'category_name'=>$value->getCategoryDetails->cat_name);
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
