<?php

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
/*Route::post('/login', 'UserController@login')->name("login");
Route::post('/register', 'UserController@login')->name("register");
Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});*/
Route::post('/login', 'UserController@login')->name("login");
Route::post('register', 'UserController@register');
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function ($api) {
        Route::group(['middleware' => 'admin'], function ($api) {
            $api->resource('category', 'CategoryController');
            $api->resource('subcategory', 'SubCategoryController');
            $api->get('subcategory_by_category/{id}', 'SubCategoryController@getByCategoryId');
            $api->resource('customer', 'CustomerController');
            $api->resource('manufacturer', 'ManufacturerController');
            $api->resource('unittype', 'UnitTypeController');
            $api->resource('items', 'ItemController');
            $api->resource('discount', 'DiscountController');
            $api->resource('tax', 'TaxController');
            $api->resource('item_quantity', 'ItemQuantityController');
            $api->resource('logs', 'LogController');
            $api->resource('purchase', 'PurchaseController');
            $api->resource('purchase_details', 'PurchaseDetailController');
            $api->post('genpurchase', 'PurchaseController@generatePurchase');
            $api->get('purchase_with_details', 'PurchaseController@getAllWithDetails');
            $api->put('refill/{id}', 'ItemController@refill');
        });
    });
});
