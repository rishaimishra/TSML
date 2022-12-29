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

// Route::post('get-po-list-admin','Api\Modules\PoDetails\PoDetailsController@getPoDetails')->name('get_po_list_admin');

Route::post('store-excel-data','Api\Modules\Bulk\BulkController@storExceleData');

Route::get('download-po-details-pdf/{id}','Api\Modules\PoDetails\PoDetailsController@downloadPoPdf')->name('download_po_details_pdf');
Route::get('gst_details_dummy','Api\Modules\Stub\StubbingController@gstDetailsDummy');
Route::get('gst_details_dummy/{gstId}','Api\Modules\Stub\StubbingController@gstDetailsDummy');
Route::get('gst_details_dummy_new','Api\Modules\Stub\StubbingController@gstDetailsDummyNew');

// Route::get('get-po-details-admin/{id}','Api\Modules\PoDetails\PoDetailsController@getPoDetailsId')->name('get_po_details_admin');

Route::post('/register', 'UserController@store');
Route::post('login', 'AuthController@login');
Route::post('send-mobile-otp', 'UserController@sendOtpToMobile')->name('send_mobile_otp');
Route::post('verify-mobile-otp', 'UserController@verifyMobileOtp')->name('verify_mobile_otp');
Route::post('password-email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::post('password-update', 'Auth\ResetPasswordController@reset')->name('user.password.update');
Route::get('get_user_by_id/{id}', 'UserController@getUserById');
Route::get('test', 'UserController@test');
Route::get('test_mail', 'UserController@testmail');
Route::post('quote_gen_mail', 'Api\Modules\QuoteEmail\QuoteEmailController@quotePoMail');
Route::post('sale_accpt_mail', 'Api\Modules\QuoteEmail\QuoteEmailController@saleAccptMail');
Route::post('accepted_price_mail', 'Api\Modules\QuoteEmail\QuoteEmailController@acceptedPriceMail');
Route::post('order_cnrfm_mail', 'Api\Modules\QuoteEmail\QuoteEmailController@orderCnrfmMail');

// ------------ sap mails------------------------------------------
Route::post('sc_mail', 'Api\Modules\QuoteEmail\QuoteEmailController@scMail');

// ---------------------------------------------------------------
Route::group(['namespace'=>'Api\Modules'],function(){
	// Index Page Routes ....
	Route::get('index-page/{proId}/{plant_id?}', 'Product\ProductController@indexPage')->name('index_page');
	Route::get('popular-product', 'Product\ProductController@popularProduct')->name('popular');
	Route::get('product-manu', 'Product\ProductController@productManu')->name('product_manu');
	Route::any('filter-product-menu', 'Product\ProductController@productFilter')->name('product_filter');
	Route::get('category-dropdown', 'Product\ProductController@CategoryDropdown')->name('category_list');
	Route::get('product-dropdown', 'Product\ProductController@ProductDropdown')->name('product_list_my');

	Route::get('product-details/{catId}/{proId}', 'Product\ProductController@productDetails')->name('product_details');
	Route::get('sub_cat_details/{subId}', 'Product\ProductController@sub_cat_details');

	Route::get('get_all_news_all','News\NewsController@getAllNews');//news list for all
   // Sayan.....
   Route::get('product-related-category-fetch','Product\ProductController@product_related');
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
    Route::get('profile', 'AuthController@me');
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
   		 Route::post('create_rfq_deliveries','Quote\QuoteController@createRfqdeliveries');
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
   		 Route::get('get_user_address/{id}','Quote\QuoteController@getUserAddress');
   		 Route::get('get_plants_by_type/{id}','Quote\QuoteController@getPlantsByType');
   		 Route::get('get_quotedel_by_id/{id}','Quote\QuoteController@getQuotedelById');
   		 Route::get('get_plant_addr/{id}','Quote\QuoteController@getPlantAddr');
   		 Route::get('reject_sche_by_date','Quote\QuoteController@rejectScheByDate');
   		 Route::get('get_all_deliveries','Quote\QuoteController@getAllDeliveries');
   		 Route::post('update_letterhead','Quote\QuoteController@updateLetterhead');
   		 Route::get('count_cus_po/{cus_po}','Quote\QuoteController@countCusPo');
   		 Route::post('update_count_requote','Requote\RequoteController@updateCountRequote');
   		 Route::get('get_count_requote/{rfq_no}','Requote\RequoteController@getCountRequote');
   		 Route::get('get_count_sche/{rfq_no}','Requote\RequoteController@getCountSche');
   		 Route::post('price_break_save','Requote\RequoteController@priceBreakSave');
   		 Route::get('get_price_comp','Requote\RequoteController@getPriceComp');
   		 Route::post('get_price_break','Requote\RequoteController@getPriceBreak');
   		 Route::post('sm_remark_save','Requote\RequoteController@smRemarkSave');
         
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

		Route::get('complain-details-kam/{po_number}/{kam_id?}', 'Complain\ComplainController@complainDetailsKam')->name('complain_details_kam');

		Route::post('send-com-mail-rm', 'Complain\ComplainController@sendComMailRm')->name('send_com_mail_rm');

		Route::get('complain-download/{complainId}', 'Complain\ComplainController@complainDownload')->name('complain_download'); 

		Route::post('get-complain-list-kam', 'Complain\ComplainController@getComplainListKam')->name('get_complain_list_kam');
		

		Route::get('closed-remarks/{complainId}', 'Complain\ComplainController@closedRemarks')->name('closed_remarks');

		Route::get('get-deparment', 'Complain\ComplainDepController@getDeparment')->name('get_deparment');
		Route::post('get-deparment-mail', 'Complain\ComplainDepController@getDeparmentMail')->name('get_mail_deparment');
   		Route::post('send-com-mail', 'Complain\ComplainManageController@sendComMail')->name('send_com_mail');
   		Route::post('com-mail-confirm', 'Complain\ComplainManageController@comMailConfirm')->name('com_mail_confirm');
   		Route::post('get-com-manage-data', 'Complain\ComplainManageController@getComManageData')->name('get_com_manage_data');
   		Route::post('store-com-files', 'Complain\ComplainManageController@storeComFiles')->name('store_com_files');


   		 Route::post('monthly_prod_plan_submit','Orders\OrderPlanningController@monthlyPlanSubmit');
   		 Route::post('prod-qty-upload','Orders\OrderPlanningController@prodQtyUpload');
   		 Route::post('get_order_planning','Orders\OrderPlanningController@getOrderPlanning');
   		 Route::post('submit_dispatch_plan','Orders\OrderPlanningController@submitDispatchPlan');
   		 Route::get('get_order_planning_by_id/{id}','Orders\OrderPlanningController@getOrderPlanById');
   		 Route::post('monthly_prod_plan_up','Orders\OrderPlanningController@monthlyPlanUpdate');


   	// ----------------- quote po notification -------------------------------------------

   		 Route::post('cam_notification_submit','Notification\NotificationController@camNotificationSubmit');
   		 Route::get('get_cam_notification/{id}','Notification\NotificationController@getCamNotification');

   		 Route::post('cus_notification_submit','Notification\NotificationController@cusNotificationSubmit');
   		 Route::get('get_cus_notification/{id}','Notification\NotificationController@getCusNotification');

   		 Route::post('sales_notification_submit','Notification\NotificationController@salesNotificationSubmit');
   		 Route::get('get_sales_notification','Notification\NotificationController@getSalesNotification');

   		 Route::post('up_cam_noti','Notification\NotificationController@upCamNoti');
   		 Route::post('up_cus_noti','Notification\NotificationController@upCusNoti');
   		 Route::post('up_sales_noti','Notification\NotificationController@upSalesNoti');
         Route::post('clear_notification','Notification\NotificationController@clearNotification');
         Route::post('opt_notification_submit','Notification\NotificationController@optNotificationSubmit');
         Route::get('get_opt_notification','Notification\NotificationController@getOptNotification');
         Route::post('plant_notification_submit','Notification\NotificationController@plantNotificationSubmit');
   	// ----------------- sap sales order --------------------------------------------
        
        Route::post('get_plant_id','Sap\SalesOrder\SalesContractController@getPlantId');

        Route::get('price_break_fetch/{po_no}','Sap\SalesOrder\SalesContractController@priceBreakFetch');
   		Route::post('sales_cnt_submit','Sap\SalesOrder\SalesContractController@salesCntSubmit');
   		// Route::get('get_price_break_by_id/{mat_no}','Sap\SalesOrder\SalesContractController@priceBreakById'); 
   		Route::post('update_contarcts_no','Sap\SalesOrder\SalesContractController@updateContarctsNo');

   		Route::get('prepare_so_list','Sap\SalesOrder\SalesContractController@prepareSoList');
   		Route::post('so_submit','Sap\SalesOrder\SalesContractController@so_submit');


   		Route::get('get_all_sc_po','Sap\SalesOrder\SalesContractController@getAllScPo');

   		Route::get('get_so_sc/{sc_no}','Sap\SalesOrder\SalesContractController@getSoSc');
   		Route::get('get_po_summary/{po_no}','Sap\SalesOrder\SapSummaryController@getPoSummary');
   		
   		
   		//------------------- Sap Routes --------------------------//

   		// Sap Contract Type Routes....
   		Route::get('get_sap_contract_type','Sap\SapContractTypeController@getSapContractType')->name('get_sap_contract_type');

   		// Sap Customer Group Routes....
   		Route::get('get_sap_customer_group','Sap\SapCustomerGroupController@getSapCustomerGroup')->name('get_sap_customer_group');

   		// Sap Delivery Mode Routes....
   		Route::get('get_sap_delivery_mode','Sap\SapDeliveryModeController@getSapDeliveryMode')->name('get_sap_delivery_mode');

   		// Sap Freight Routes....
   		Route::get('get_sap_freight','Sap\SapFreightController@getSapFreight')->name('get_sap_freight');

   		// Sap Freight Indication Routes....
   		Route::get('get_sap_freight_indi','Sap\SapFreightIndicationController@getSapFreightIndi')->name('get_sap_freight_indi');

   		// Sap Incoterms Routes....
   		Route::get('get_sap_incoterms','Sap\SapIncotermsController@getSapIncoterms')->name('get_sap_incoterms');

   		// Sap Payment Terms Routes....
   		Route::get('get_sap_sales_group','Sap\SapSalesGroupController@getSapSalesGroup')->name('get_sap_sales_group');

   		// Sap Sales Organization Controller Routes....
   		Route::get('get-sap-sales-org','Sap\SalesOrganizationController@getSalesOrgType')->name('get_sap_sales_org');

   		// Sap Sales Office Controller Routes....
   		Route::get('get-sales-office','Sap\SalesOfficeController@getSalesOffice')->name('get_sales_office');

   		// Sap Division Controller Routes....
   		Route::post('get-sap-division','Sap\DivisionController@getSapDivision')->name('get_sap_division');
   		// Sap Distribution Channel Controller Routes....
   		Route::get('get-distri-channel','Sap\DistributionChannelController@getDistriChannel')->name('get_distri_channel');

   		// Sap Order Type Controller Routes....
   		Route::get('get-order-type','Sap\SapOrderTypeController@getOrderType')->name('get_order_type');

   		// Sap Payment Terms Controller Routes....
   		Route::get('get-sap-payment-terms','Sap\SapPaymentTermsController@getPaymentTerms')->name('get_sap_payment_terms');

   		// Sap Payment Gurantee Procedure Controller Routes....
   		Route::get('get-pay-gurantee-pos','Sap\SapPaymentGuranteeProcedureController@getPayGuranteePos')->name('get_pay_gurantee_pos');
       

   		//------------------- End of Sap Routes -------------------//

   		//------------------- Dorder Routes --------------------------//

   		Route::post('store-do','Dorder\DoController@storeDo')->name('store_do');
   		Route::post('get-do-details','Dorder\DoController@getDoDetails')->name('get_do-details');
   		 Route::get('get_do_sub_cats/{so_no}','Dorder\DoController@getDoSubCats');
   		Route::get('get_all_do/{id}','Dorder\DoController@getAllDo');
   		Route::get('get_all_do_for_cus/{id}','Dorder\DoController@getAllDoCus');
   		Route::get('get_do_by_cus/{id}','Dorder\DoController@get_do_by_cus');
   		Route::get('get_do_by_cam/{id}','Dorder\DoController@get_do_by_cam');
   		Route::get('get_all_so','Dorder\DoController@get_all_so');
         Route::post('validate_do_qty','Dorder\DoController@validateDo');
   		//------------------- End of Dorder Routes --------------------------//



         //------------------- RFQ Order Status Kam -------------------//
         
         Route::post('store-order-status-kam','RfqOrderStatus\RfqOrderStatusController@storeOrderStatusKam')->name('store_rfq_order_status_kam');
         Route::post('get-rfq-order-status-kam','RfqOrderStatus\RfqOrderStatusController@getRfqOrderStatusKam')->name('get_rfq_order_status_kam');

         //------------------- End of RFQ Order Status Kam -------------------//


         //------------------- RFQ Order Status Customer -------------------//
         Route::post('store-rfq-order-status-cust','RfqOrderStatus\RfqOrderStatusController@StoreRfqOrderStatusCust')->name('store_rfq_order_status_cust');
         Route::post('get-rfq-order-status-cust','RfqOrderStatus\RfqOrderStatusController@getRfqOrderStatuCust')->name('get_rfq_order_status_cust');

         //------------------- End of RFQ Order Status Customer -------------------//

         // -------------------- search ----------------------------------------
          
          Route::post('rfq_search_list','Search\SearchController@cusRfqSearchList');

          Route::post('po_search_list','Search\SearchController@poSearchList');

         // ---------------------------------------------------------------------
         // ----------------------- production fg -------------------------------
           Route::post('prod-qty-upload-user','Orders\OrderPlanningController@prodQtyUploadAdmin');
          // -------------------------------------------------------------------
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
      Route::post('update-product/{proId}', 'Product\ProductController@updateProduct')->name('update_product');
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
		Route::get('get-subcategory-size-byid/{subcateId}','PriceManagement\PriceManagementController@getSubCategorySizeByid')->name('get_subcategory_size_byid'); 
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

		Route::post('get-po-list-admin','PoDetails\PoDetailsController@getPoDetails')->name('get_po_list_admin');

		Route::get('download-po-details-pdf/{id}','PoDetails\PoDetailsController@downloadPoPdf')->name('download_po_details_pdf');

		Route::get('get-po-details-admin/{id}','PoDetails\PoDetailsController@getPoDetailsId')->name('get_po_details_admin');
		
	});

		

});
