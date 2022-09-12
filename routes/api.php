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

Route::post('/register', 'UserController@store');
Route::post('login', 'AuthController@login');
Route::post('send-mobile-otp', 'UserController@sendOtpToMobile')->name('send_mobile_otp');
Route::post('verify-mobile-otp', 'UserController@verifyMobileOtp')->name('verify_mobile_otp');
Route::post('password-email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
Route::post('password-update', 'Auth\ResetPasswordController@reset')->name('user.password.update');


Route::group(['namespace'=>'Api\Modules'],function(){
	// Index Page Routes ....
	Route::get('index-page/{proId}', 'Product\ProductController@indexPage')->name('index_page');
	Route::get('product-manu', 'Product\ProductController@productManu')->name('product_manu');
});
 

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
//  });
  
//  Route::group([
  
//     'middleware' => 'api',
//     'jwt.auth'
  
//  ], function ($router) {
  
    

//  });

 Route::group(['prefix' => 'user','middleware' => ['assign.guard:users', 'jwtmiddleware']],function ()
{
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
   	Route::resource('customer', 'UserController');	
   	Route::post('customers/{id}', 'UserController@update');	
   	Route::post('reset-password', 'UserController@resetPassword')->name('reset_password');
});

// Admin Routes....
Route::post('admin-login', 'AdminAuthController@Adminlogin')->name('admin_login');
Route::post('admin-register', 'AdminAuthController@Adminregister')->name('admin_register');

Route::group(['prefix' => 'admin','middleware' => ['assign.guard:admins','jwtmiddleware']],function (){
	Route::get('/demo','AdminController@demo');	
	Route::post('admin-logout', 'AdminController@logout')->name('admin_logout');
	Route::post('user_status_update', 'AdminController@userStatusUpdate');

	Route::group(['namespace'=>'Api\Modules'],function(){

		// Category Routes....
		Route::post('store-category', 'Category\CategoryController@storeCategory')->name('store_category');
		Route::get('category-list', 'Category\CategoryController@categoryList')->name('category_list');
		Route::get('edit-category/{catId}', 'Category\CategoryController@editCategory')->name('edit_category');
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
		Route::get('sub-category-list-my', 'SubCategory\SubCategoryController@subCategoryListMy')->name('sub_category_list_my');

		// Product Routes ....
		Route::post('store-product', 'Product\ProductController@storeProduct')->name('store_product');
		Route::get('edit-product/{proId}', 'Product\ProductController@editProduct')->name('edit_product');
		Route::post('update-product/{proId}', 'Product\ProductController@updateProduct')->name('update_product');
		Route::get('delete-product/{proId}', 'Product\ProductController@deleteProduct')->name('delete_product');
		Route::get('product-list', 'Product\ProductController@productList')->name('product_list');
		Route::get('inactive-product/{proId}', 'Product\ProductController@inactiveProduct')->name('inactive_product');
		Route::get('active-product/{proId}', 'Product\ProductController@activeProduct')->name('active_product');
		Route::get('product-list-my', 'Product\ProductController@productListMy')->name('product_list_my');

	});

});
