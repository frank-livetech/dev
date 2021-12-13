<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
Route::middleware('api')->get('/testing', function (Request $request) {
    return 'testing';
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','namespace' => 'API'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@register');
    
    // wp_customers
    Route::post('/customer_create','wooCommerceController@wp_customer_create');
    Route::post('/customer_update','wooCommerceController@wp_customer_update');
    Route::post('/customer_delete','wooCommerceController@wp_customer_delete');
    
    //wp orders
    Route::post('/order_create','wooCommerceController@wp_order_create');
    Route::post('/order_update','wooCommerceController@wp_order_update');
    Route::post('/order_delete','wooCommerceController@wp_order_delete');
    
    // wp products
    Route::post('/product_create','wooCommerceController@wp_product_create');
    Route::post('/product_update','wooCommerceController@wp_product_update');
    Route::post('/product_delete','wooCommerceController@wp_product_delete');

  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@getAuthUser');
        Route::post('edit_profile', 'AuthController@updateProfile');
        Route::get('refreshToken', 'AuthController@refresh');
        Route::post('create_ticket', 'Ticket\TicketController@addTicket');

        Route::get('departments', 'Ticket\TicketController@departments');
        Route::get('priorities', 'Ticket\TicketController@priorities');
        Route::get('tickets', 'Ticket\TicketController@tickets');


    });
});

