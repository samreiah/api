<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	phpinfo();
	dd();
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['prefix' => '/customer'], function () {
	
	Route::post('authenticate', 'CustomerController@authenticate');
	Route::post('logout', 'CustomerController@logout');
	Route::post('create', 'CustomerController@create');
	
	Route::get('get/{customer_id}', 'CustomerController@getCustomer');
	Route::get('get', 'CustomerController@getCustomers');
	
	Route::get('getAddressById/{address_id}', 'CustomerProfileController@getAddressById');
	Route::get('getAddressByCId/{customer_id}', 'CustomerProfileController@getAddressByCId');
	
	Route::group(['prefix' => '/address'], function () {
		Route::get('makedefault/{customer_id}/{address_id}', 'CustomerProfileController@makeAddressDefault');
		Route::get('delete/{customer_id}/{address_id}', 'CustomerProfileController@deleteAddress');
		Route::post('edit/post', 'CustomerProfileController@updateAddress');
		Route::post('add/post', 'CustomerProfileController@addAddress');
	});

	
	Route::group(['prefix' => '/profile'], function() {
		Route::post('post', 'CustomerProfileController@editProfilePost');
	});
	
	Route::group(['prefix' => '/password'], function() {
		Route::post('reset/post', 'CustomerProfileController@passwordResetPost');
	});
	
});

Route::group(['prefix' => '/affiliate'], function () {
	
	Route::get('get/{affiliate_id}', 'AffiliateController@getAffiliate');
	Route::get('get', 'AffiliateController@getAffiliates');
	
	Route::post('authenticate', 'AffiliateController@authenticate');
	Route::post('logout', 'AffiliateController@logout');
	Route::post('create', 'AffiliateController@create');
	
	Route::group(['prefix' => '/profile'], function() {
		Route::post('post', 'AffiliateProfileController@editProfilePost');
		Route::post('company/post', 'AffiliateProfileController@editCompanyProfilePost');
	});
	
	Route::group(['prefix' => '/password'], function() {
		Route::post('reset/post', 'AffiliateProfileController@passwordResetPost');
	});
	
});


Route::group(['prefix' => '/admin'], function () {
	
	Route::get('get/{affiliate_id}', 'AdminController@getAdmin');
	
	Route::post('authenticate', 'AdminController@authenticate');
	Route::post('logout', 'AdminController@logout');
	Route::post('create', 'AdminController@create');
	
	Route::group(['prefix' => '/profile'], function() {
		Route::post('post', 'AdminProfileController@editProfilePost');
	});
	
	Route::group(['prefix' => '/password'], function() {
		Route::post('reset/post', 'AdminProfileController@passwordResetPost');
	});
	
});

Route::group(['prefix' => '/country'], function() {
	
	Route::get('get', 'CountryController@getCountries');
	Route::get('get/{countryId}', 'CountryController@getCountry');
	
});

Route::group(['prefix' => '/password'], function() {
	
	Route::post('reset', 'PasswordResetController@postEmail');
	Route::post('reset/post', 'PasswordResetController@postReset');
});

Route::group(['prefix' => '/category'], function() {
	
	Route::group(['prefix' => 'search'], function() {
		Route::get('{product_slug}', 'CategoryController@getProductsByCategorySlug'); 
	});
	
	Route::post('create', 'CategoryController@createCategory');
	Route::get('get', 'CategoryController@getCategories');
	Route::get('get/active', 'CategoryController@getActiveCategories');
	Route::get('get/top', 'CategoryController@getTopCategories');

	Route::get('get/top/products', 'CategoryController@getTopProducts');
	
});

Route::group(['prefix' => '/product'], function() {
	Route::get('getbyid/{product_id}', 'ProductController@getProductById');
	Route::get('getallreviews/{product_id}', 'ProductController@getProductReviews');
});

Route::group(['prefix' => '/cart'], function() {
	
	Route::get('items/customer/{customer_id}', 'CartController@getCartItemsByCustomer');
	Route::get('items/cartids/{cart_id}', 'CartController@getCartItemsByIds');
	Route::get('update/cartids/{cart_ids}/{customer_id}', 'CartController@updateCustomerId');
	Route::get('item/delete/{cart_id}', 'CartController@deleteCartItem');
	Route::get('item/update/quantity/{cart_id}/{quantity}', 'CartController@updateQuantity');
	Route::post('add','CartController@addCartItem');
	Route::post('add/from/wishlist','CartController@addFromWishlist');
});

Route::group(['prefix' => '/wishlist'], function() {
	
	Route::get('items/customer/{customer_id}', 'WishlistController@getWishlistItemsByCustomer');
	Route::get('items/wishlistids/{wishlist_id}', 'WishlistController@getWishlistItemsByIds'); 
	Route::get('update/wishlistids/{wishlist_ids}/{customer_id}', 'WishlistController@updateCustomerId');
	Route::get('item/delete/{wishlist_id}', 'WishlistController@deleteWishlistItem');
	Route::get('item/update/quantity/{wishlist_id}/{quantity}', 'WishlistController@updateQuantity');
	Route::post('add','WishlistController@addWishlistItem');
	Route::post('add/from/cart','WishlistController@addFromCart');
});

Route::group(['prefix' => '/rewards'], function() {
	
	Route::post('customer', 'RewardsController@customerRewards');
	Route::post('customer/rewardpoints', 'RewardsController@customerRewardPoints');
	Route::post('redeem/coupon/post', 'RewardsController@redeemCoupon');
	
});

Route::group(['prefix' => 'checkout'], function() {

	Route::post('checkpincode', 'CheckoutController@checkPincodeDelivery');
	Route::post('calculate/total', 'CheckoutController@calculateGrandTotal');
	Route::post('placeorder', 'CheckoutController@placeOrder');
	
});


Route::group(['prefix' => 'orders'], function() {
	Route::get('all/{customer_id}', 'OrderController@allOrders');
	Route::get('track/order/{order_product_id}', 'OrderController@trackOrder');
});

Route::group(['prefix' => 'reviews'], function() {
	Route::get('all/{customer_id}', 'ReviewController@allReviews');
	Route::get('is_valid/{customer_id}/{order_product_id}', 'ReviewController@is_valid');
	Route::post('write/review', 'ReviewController@writeReview');
});

Route::group(['prefix' => 'shippingmethod'], function() {
	Route::get('getbyid/{method_id}', 'ShippingMethodController@getShippingMethodById');
	Route::get('all', 'ShippingMethodController@getAllShippingMethods');
	Route::get('active', 'ShippingMethodController@getActiveShippingMethods');
});

Route::group(['prefix' => 'paymentmethod'], function() {
	Route::get('getbyid/{method_id}', 'PaymentMethodController@getPaymentMethodById');
	Route::get('all', 'PaymentMethodController@getAllPaymentMethods');
	Route::get('active', 'PaymentMethodController@getActivePaymentMethods');
});

Route::group(['prefix' => 'shippingrule'], function() {
	Route::get('all', 'ShippingRuleController@getAllShippingRules');
	Route::get('active', 'ShippingRuleController@getActiveShippingRules');
});
