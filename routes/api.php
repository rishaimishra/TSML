<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('get-po-list-admin','Api\Modules\PoDetails\PoDetailsController@getPoDetails')->name('get_po_list_admin');

Route::get('download-po-details-pdf/{id}','Api\Modules\PoDetails\PoDetailsController@downloadPoPdf')->name('download_po_details_pdf');

Route::get('get-po-details-admin/{id}','Api\Modules\PoDetails\PoDetailsController@getPoDetailsId')->name('get_po_details_admin');

Route::post('/register', 'UserController@store');
Route::post('login', 'AuthController@login');
Route::post('send-mobile-otp', 'UserController@sendOtpToMobile')->name('send_mobile_otp');
Route::post('verify-mobile-otp', 'UserController@verifyMobileOtp')->name('verify_mobile_otp');
Route::post('password-email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::post('password-update', 'Auth\ResetPasswordController@reset')->name('user.password.update');


Route::group(['namespace'=>'Api\Modules'],function(){
	// Index Page Routes ....
	Route::get('index-page/{proId}', 'Product\ProductController@indexPage')->name('index_page');
	Route::get('popular-product', 'Product\ProductController@popularProduct')->name('popular');
	Route::get('product-manu', 'Product\ProductController@productManu')->name('product_manu');
	Route::any('filter-product-menu', 'Product\ProductController@productFilter')->name('product_filter');
	Route::get('category-dropdown', 'Product\ProductController@CategoryDropdown')->name('category_list');
	Route::get('product-dropdown', 'Product\ProductController@ProductDropdown')->name('product_list_my');

	Route::get('product-details/{catId}/{proId}', 'Product\ProductController@productDetails')->name('product_details');

	Route::get('get_all_news_all','News\NewsController@getAllNews');//news list for all
});
 

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
//  });
  
//  Route::group([
  
//     'middleware' => 'api',
//     'jwt.auth'
  
//  ], function ($router) {
  
    

//  });

Route::get('download-po-pdf/{id}','Api\Modules\Quote\QuoteController@downloadPdf')->name('downloadPdf');

Route::group(['prefix' => 'user','middleware' => ['assign.guard:users', 'jwtmiddleware']],function ()
{
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
   	Route::resource('customer', 'UserController');	
   	Route::post('customers/{id}', 'UserController@update');	
   	Route::post('reset-password', 'UserController@resetPassword')->name('reset_password');

   	Route::group(['namespace'=>'Api\Modules'],function(){
   		// Route::get('download-po-pdf/{id}','Quote\QuoteController@downloadPdf')->name('downloadPdf');
   		 Route::post('store_quotes','Quote\QuoteController@storeQuotes');
   		 Route::post('update_quotes','Quote\QuoteController@updateQuotes');
   		 Route::post('quotes_status_update','Quote\QuoteController@quotesStatusUpdate');
   		 Route::get('quotes_history/{rfq_no}','Quote\QuoteController@quotesHistoryCustomer');
   		 Route::get('quotes_list','Quote\QuoteController@getQuotesList');
   		 Route::get('get_quote_by_id/{id}','Quote\QuoteController@getQuoteById');
   		 Route::post('update_quotes_sche','Quote\QuoteController@updateQuoteSche');
   		 Route::post('submit_requote_id','Quote\QuoteController@submitRequoteId');
   		 Route::get('get_requote_list','Quote\QuoteController@getRequoteList');
   		 Route::post('update_requote','Quote\QuoteController@updateRequote');
   		 Route::post('delete_quote_by_id','Quote\QuoteController@deleteQuoteById');
   		 Route::get('get_quote_sche_by_id/{id}','Quote\QuoteController@getQuoteScheById');
   		 Route::post('delete_quote_sche','Quote\QuoteController@deleteQuoteSche');
   		 Route::get('kam_quotes_list','Quote\QuoteController@getKamQuotesList');
   		 Route::get('view_remarks/{rfq_no}','Quote\QuoteController@viewRemarks');
   		 Route::get('get_quote_po_by_id/{id}','Quote\QuoteController@getPoQuoteById');
   		 Route::post('submit_po','Quote\QuoteController@submitPo');
   		 Route::get('get_po_by_id/{id}','Quote\QuoteController@getPoById');
   		 Route::get('get_po_all','Quote\QuoteController@getPoAll');
   		 Route::get('get_po_all_kam','Quote\QuoteController@getPoAllKam');
   		 Route::post('po_status_update','Quote\QuoteController@poStatusUpdate');
   		 Route::post('update_po','Quote\QuoteController@updatePo');
   		 Route::post('sales_update_rfq','Quote\QuoteController@salesUpdateRfq');

   		 Route::post('get-store-pro-price','PriceManagement\PriceManagementController@getProPrice')->name('get_store_pro_price');
   		 Route::get('get-threshold-price','PriceManagement\PriceManagementController@getThresholdPrice')->name('get_threshold_price');
   		 // Complain Remarks Routes....
   		 Route::get('complain-category-list', 'Complain\ComplainController@getComplainCategory')->name('complain_category');
   		 Route::get('complain-sub-category-list/{id}', 'Complain\ComplainController@getComplainSubCategory')->name('complain_sub_category');
   		 Route::get('complain-sub-category2-list/{id}', 'Complain\ComplainController@getComplainSubCategory2')->name('complain_sub_category2');
   		 Route::get('complain-sub-category3-list/{id}', 'Complain\ComplainController@getComplainSubCategory3')->name('complain_sub_category3');

   		Route::post('store-complain-main', 'Complain\ComplainController@storeComplainMain')->name('store_complain_main');

		Route::post('remarks-replay', 'Complain\ComplainController@remarksReplay')->name('remarks_replay');

		Route::get('complain-details/{complainId}', 'Complain\ComplainController@complainDetails')->name('complain_details');

		Route::get('complain-details-kam/{po_number}', 'Complain\ComplainController@complainDetailsKam')->name('complain_details_kam');

		Route::get('complain-download/{complainId}', 'Complain\ComplainController@complainDownload')->name('complain_download'); 

		Route::get('get-complain-list-kam', 'Complain\ComplainController@getComplainListKam')->name('get_complain_list_kam');
		

		Route::get('closed-remarks/{complainId}', 'Complain\ComplainController@closedRemarks')->name('closed_remarks');

   		  


   		 Route::post('monthly_prod_plan_submit','Orders\OrderPlanningController@monthlyPlanSubmit');
   		 Route::post('prod-qty-upload','Orders\OrderPlanningController@prodQtyUpload');
   		 Route::post('get_order_planning','Orders\OrderPlanningController@getOrderPlanning');
   		 Route::post('submit_dispatch_plan','Orders\OrderPlanningController@submitDispatchPlan');
   		 Route::get('get_order_planning_by_id/{id}','Orders\OrderPlanningController@getOrderPlanById');
   		 Route::post('monthly_prod_plan_up','Orders\OrderPlanningController@monthlyPlanUpdate');

     });
   	
});

// Admin Routes....
Route::post('admin-login', 'AdminAuthController@Adminlogin')->name('admin_login');
Route::post('admin-register', 'AdminAuthController@Adminregister')->name('admin_register');
Route::post('user-bulk-upload','Api\Modules\Bulk\BulkController@storeUser');

Route::group(['prefix' => 'admin','middleware' => ['assign.guard:admins','jwtmiddleware']],function (){
	Route::get('/demo','AdminController@demo');	
	Route::post('admin-logout', 'AdminController@logout')->name('admin_logout');
	Route::post('user_status_update', 'AdminController@userStatusUpdate');

	Route::group(['namespace'=>'Api\Modules'],function(){

		// Category Routes....
		Route::post('store-category', 'Category\CategoryController@storeCategory')->name('store_category');
		Route::get('category-list', 'Category\CategoryController@categoryList')->name('category_list');
		Route::put('edit-category/{catId}', 'Category\CategoryController@editCategory')->name('edit_category');
		Route::post('update-category/{catId}', 'Category\CategoryController@updateCategory')->name('update_category');
		Route::get('inactive-category/{catId}', 'Category\CategoryController@inactiveCategory')->name('inactive_category');
		Route::get('active-category/{catId}', 'Category\CategoryController@activeCategory')->name('active_category');
		Route::get('category-list-my', 'Category\CategoryController@categoryListMy')->name('category_list_my');

		// Sub Category Routes ....
		Route::post('store-sub-category', 'SubCategory\SubCategoryController@storeSubCategory')->name('store_sub_category');
		Route::get('sub-category-list', 'SubCategory\SubCategoryController@subCategoryList')->name('sub_category_list');
		Route::get('edit-sub-category/{subCatId}', 'SubCategory\SubCategoryController@editSubCategory')->name('edit_sub_category');

		Route::post('update-sub-category/{subCatId}', 'SubCategory\SubCategoryController@updateSubCategory')->name('update_sub_category');

		Route::get('inactive-sub-category/{subCatId}', 'SubCategory\SubCategoryController@inactiveSubCategory')->name('inactive_sub_category');
		Route::get('active-sub-category/{subCatId}', 'SubCategory\SubCategoryController@activeSubCategory')->name('active_sub_category');
		Route::get('delete-sub-category/{subCatId}', 'SubCategory\SubCategoryController@deleteSubCategory')->name('delete_sub_category');	
		Route::get('sub-category-list-my', 'SubCategory\SubCategoryController@subCategoryListMy')->name('sub_category_list_my');

		// Product Routes ....
		Route::post('store-product', 'Product\ProductController@storeProduct')->name('store_product');
		Route::put('edit-product/{proId}', 'Product\ProductController@editProduct')->name('edit_product');
		Route::get('delete-product/{proId}', 'Product\ProductController@deleteProduct')->name('delete_product');
		Route::get('product-list', 'Product\ProductController@productList')->name('product_list');
		Route::get('inactive-product/{proId}', 'Product\ProductController@inactiveProduct')->name('inactive_product');
		Route::get('active-product/{proId}', 'Product\ProductController@activeProduct')->name('active_product');
		Route::get('product-list-my', 'Product\ProductController@productListMy')->name('product_list_my');

		// Freights Routes....
		Route::post('store-freight', 'Freight\FreightController@storeFreights')->name('store_freights');
		Route::get('get-freights', 'Freight\FreightController@getFreights')->name('get_freights');

		Route::put('edit-freight/{id}', 'Freight\FreightController@editFreights')->name('edit_freights');
		Route::post('update-freight', 'Freight\FreightController@updateFreights')->name('update_freights');
		Route::get('active-freight/{id}', 'Freight\FreightController@activeFreights')->name('active_freights');
		Route::get('inactive-freight/{id}', 'Freight\FreightController@inactiveFreights')->name('inactive_freights');
		Route::get('delete-freight/{id}', 'Freight\FreightController@deleteFreights')->name('delete_freights');

		// Price Management Routes....
		Route::get('get-product-list', 'PriceManagement\PriceManagementController@getProductList')->name('get_product_list');
		Route::get('get-category-list/{proId}','PriceManagement\PriceManagementController@getCategoryList')->name('get_category_list'); 
		Route::get('get-sub-category-list/{cateId}','PriceManagement\PriceManagementController@getSubCategoryList')->name('get_sub_category_list'); 
		Route::post('store-price','PriceManagement\PriceManagementController@storePrice')->name('store_price');
		Route::post('store-threshold-price','PriceManagement\PriceManagementController@storeThreshold')->name('store_threshold_price');
		Route::post('store-pro-price','PriceManagement\PriceManagementController@manageProPrice')->name('store_pro_price'); //For Admin asection
		Route::get('get-threshold-price-admin','PriceManagement\PriceManagementController@getThresholdPriceAdmin')->name('get_threshold_price_admin'); //For Admin section....
		Route::post('get-store-pro-price','PriceManagement\PriceManagementController@getProPrice')->name('get_store_pro_price');
		Route::get('get-threshold-price-details-admin/{ThresholdId}','PriceManagement\PriceManagementController@getThresholdPriceDetailsAdmin')->name('get_threshold_price_details_admin'); //For Admin section....
		Route::post('update-threshold-price-admin','PriceManagement\PriceManagementController@updateThresholdProPrice')->name('update_threshold_price_admin'); //For Admin section....
		Route::get('get-price','PriceManagement\PriceManagementController@getPrice')->name('get_price');  //P  
		Route::post('get-product-basic-price','PriceManagement\PriceManagementController@getProductBasicPrice')->name('get_product_basic_price');

		// news Routes -------

		Route::post('store_news','News\NewsController@storeNews');
		Route::get('get_all_news','News\NewsController@getAllNews');
		Route::get('get_news_by_id/{id}','News\NewsController@getNewsById');
		Route::post('update_news','News\NewsController@updateNews');
		Route::delete('delete_news/{id}','News\NewsController@deleteNews');

		// Complain Routs...
		Route::post('store-complain-category', 'Complain\ComplainController@storeComplainCategory')->name('store_complain_category');
		 
		Route::post('store-complain-sub-category', 'Complain\ComplainController@storeComplainSubCategory')->name('store_complain_sub_category');
		
		Route::post('store-complain-sub-category2', 'Complain\ComplainController@storeComplainSubCategory2')->name('store_complain_sub_category2');
		
		Route::post('store-complain-sub-category3', 'Complain\ComplainController@storeComplainSubCategory3')->name('store_complain_sub_category3'); 
		// Product qut upload exl...
		Route::post('prod-qty-upload-admin','Orders\OrderPlanningController@prodQtyUploadAdmin');

		// PO Details Routes... 

		// Route::get('get-po-list-admin','PoDetails\PoDetailsController@getPoDetails')->name('get_po_list_admin');

		// Route::get('get-po-details-admin/{id}','PoDetails\PoDetailsController@getPoDetailsId')->name('get_po_details_admin'); 
		
	});

		

});
