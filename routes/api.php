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

Route::post('register', 'UserController@store');
Route::post('login', 'AuthController@login');

Route::group(['namespace'=>'Api\Modules'],function(){

	// Category Routes....
	Route::post('store-category', 'Category\CategoryController@storeCategory')->name('store_category');
	Route::get('category-list', 'Category\CategoryController@categoryList')->name('category_list');
	Route::put('edit-category/{catId}', 'Category\CategoryController@editCategory')->name('edit_category');
	Route::get('inactive-category/{catId}', 'Category\CategoryController@inactiveCategory')->name('inactive_category');
	Route::get('active-category/{catId}', 'Category\CategoryController@activeCategory')->name('active_category');
	Route::get('category-list-my', 'Category\CategoryController@categoryListMy')->name('category_list_my');

	// Sub Category Routes ....
	Route::post('store-sub-category', 'SubCategory\SubCategoryController@storeSubCategory')->name('store_sub_category');
	Route::get('sub-category-list', 'SubCategory\SubCategoryController@subCategoryList')->name('sub_category_list');
	Route::put('edit-sub-category/{subCatId}', 'SubCategory\SubCategoryController@editSubCategory')->name('edit_sub_category');
	Route::get('inactive-sub-category/{subCatId}', 'SubCategory\SubCategoryController@inactiveSubCategory')->name('inactive_sub_category');
	Route::get('active-sub-category/{subCatId}', 'SubCategory\SubCategoryController@activeSubCategory')->name('active_sub_category');	
	Route::get('sub-category-list-my', 'SubCategory\SubCategoryController@subCategoryListMy')->name('sub_category_list_my');

});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
//  });
  
//  Route::group([
  
//     'middleware' => 'api',
//     'jwt.auth'
  
//  ], function ($router) {
  
    

//  });

 Route::group(['prefix' => 'user','middleware' => ['assign.guard:users','jwt.auth']],function ()
{
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
   Route::resource('customer', 'UserController');	
});

Route::group(['prefix' => 'admin','middleware' => ['assign.guard:admins','jwt.auth']],function ()
{
	Route::get('/demo','AdminController@demo');	
});
