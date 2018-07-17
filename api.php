<?php

use Illuminate\Http\Request;
use App\cleanUrl;

// Route::middleware('auth:api')->get('/user', function (Request $request) {  return $request->user(); });
// Route::post('/customer',[ 'uses'	=>	'FrontControllers\ProfileController@signup' ]);
// Route::post('/customer/signin',[ 'uses'	=>	'FrontControllers\ProfileController@signin'  ]);

Route::post('/contactus',['uses' => 'AngularControllers\ContactUsController@store' ]);
Route::get('/footercontent/staticPages',[  'uses' => 'AngularControllers\Footercontent@staticPages' ]);


/**************************************************  CATEGORY AND SUBCATEGORY SEARCH SECTION ****************************************************/

Route::get('/quickSearch',['uses' => 'AngularControllers\SearchController@quickSearch' ]);
Route::get('/searchStr',['uses' => 'AngularControllers\SearchController@getSearchStr' ]);
Route::get('/filter',['uses' => 'AngularControllers\SearchController@Filter' ]);
Route::get('/subCategorydata/{para}', function (Request $request, $para) {
	$RES = CleanUrl::where('cleanURL','=',$para)->first();
	$subcat = $RES->category_id;
	$pid = 1;
	$newpara = $request->input('newpara');
	$metalCHK = $request->input('metalCHK');
	if(empty($metalCHK[1])){
		$metalCHK[1] = 0;
	}
	if(!empty($pid)){
		$result = App::call('\App\Http\Controllers\AngularControllers\CategoryController@subCategorydata', array(array('cat_id'=>$pid, 'subcat'=>$subcat, 'newpara'=>$newpara,  'metalCHK'=>$metalCHK[1])));
		return $result;
	}else{
		$result = array('notfound'=>1);
		return json_encode($result);
	} 
});

/***************************************************************  CATEGORY AND PRODUCT DETAIL SECTION  *************************************************************************/

Route::get('/categoryFilters',[ 'uses' => 'AngularControllers\CategoryController@categoryFilters' ]);
Route::get('/styleAttribute',[ 'uses' => 'AngularControllers\CategoryController@styleAttribute' ]);
Route::get('/shapeAttribute',[ 'uses' => 'AngularControllers\CategoryController@shapeAttribute' ]);
Route::get('/metalAttribute',[ 'uses' => 'AngularControllers\CategoryController@metalAttribute' ]);
Route::get('/stoneTypeAttribute',[ 'uses' => 'AngularControllers\CategoryController@stoneTypeAttribute' ]);
Route::get('/MensAttribute',[ 'uses' => 'AngularControllers\CategoryController@MensAttribute' ]);
Route::get('/LadiesAttribute',[ 'uses' => 'AngularControllers\CategoryController@LadiesAttribute' ]);
Route::get('/currency','AngularControllers\CommonController@getCurrency');
Route::get('/findCurrency', 'AngularControllers\CommonController@getCurrencyValue');
Route::get('/saveBagProducts','AngularControllers\CartController@addBagProducts');
Route::get('/allcartData','AngularControllers\CartController@index');
Route::get('/removeOrderItem','AngularControllers\CartController@removeItem');
Route::get('/updatEengraving','AngularControllers\CartController@updateEngraving');
Route::get('/SaveEengravingData','AngularControllers\CartController@savedata');
Route::get('/itemQuantityData','AngularControllers\CartController@itemQuantityData');
Route::get('/SaveAdandonCart','AngularControllers\CartController@saveAdandonCart');
Route::post('/getPaymentResponse','AngularControllers\ResponseController@getPaymentResponse');
Route::get('/getPaymentResponse','AngularControllers\ResponseController@getPaymentResponse');
Route::get('/addToFavourite','AngularControllers\ProductController@addtofavourite');
Route::get('/products',[ 'uses'	=> 'FrontControllers\ProductController@getproduct' ]);
Route::get('/pagesnproducts/{slug}.html', function ($slug) {
	$newslug = $slug.'.html';
	$RES = cleanUrl::where('cleanUrl','=',$newslug)->first();
	$data = array('page_id' =>$RES->page_id,'slug'=>$newslug,'category_id' =>$RES->category_id,'product_id' =>$RES->product_id);
	if(!empty($RES->page_id)){
		$result = App::call('\App\Http\Controllers\AngularControllers\PagesController@staticPages', array(array('page_id'=>$RES->page_id,'page_id2'=>$RES->page_id)));
		return $result;
	}elseif(!empty($RES->product_id)){
		$result = App::call('\App\Http\Controllers\AngularControllers\ProductController@productData',  array(array('product_id'=>$RES->product_id,'slug'=>$slug)));
		return $result;
	}else{
		$result = array('notfound'=>1);
		return json_encode($result);
	}	
 });
Route::get('/categorydata/{slug}', function ($slug) {
	$RES = cleanUrl::where('cleanUrl','=',$slug)->first();
	if(!empty($RES->category_id)){
		$result = App::call('\App\Http\Controllers\AngularControllers\CategoryController@mainCategory', array(array('cat_id'=>$RES->category_id)));
		return $result;
	}else{
		$result = array('notfound'=>1);
		return json_encode($result);
	}	
});
Route::get('/productPrice/', function (Request $request) {
		return $result = App::call('\App\Http\Controllers\AngularControllers\ProductController@productPrice');
});

/***************************************************************  Login Registratioon  *************************************************************************/
Route::post('/registration',[ 'uses' => 'AngularControllers\CustomerRegistrationController@store' ]);
Route::get('/CustomerToken',[ 'uses' => 'AngularControllers\CommonController@createCustomerToken' ]);
Route::group(['middleware' => 'api'], function () {
	Route::post('customerlogin', 'AuthController@login');
	Route::post('customerData', 'AuthController@me');
	Route::get('logout', 'AuthController@logout');
	//Route::post('refresh', 'AuthController@refresh');
	Route::post('me', 'AuthController@me');
	Route::get('/getCustomerOrderDetails','AngularControllers\AfterLoginController@getCustomerOrderDetails');
});

//For update customer address
Route::post('/updatecustaddress/{id}','AngularControllers\AfterLoginController@updatecustaddress');
Route::get('/addressData/{cust_id}','AngularControllers\AfterLoginController@getAddress');
//For Deleing Address record
Route::get('/addressDelete/{id}','AngularControllers\AfterLoginController@addressDelete');
Route::get('/cust_address/{id}','AngularControllers\AfterLoginController@cust_address');
//For Order Data
Route::get('/orderData/{profile_id}','AngularControllers\AfterLoginController@orderData');
//For Update current login user session tble data
Route::get('/updateCustomerSessionData/{profile_id}','AngularControllers\AfterLoginController@updateCustomerSessionData');
//For Order Details Data
Route::get('/orderDetailsData/{id}','AngularControllers\AfterLoginController@orderDetailsData');
Route::post('/update_session_data_table',['uses' => 'AngularControllers\AfterLoginController@update_session_data_table']);
 
 
/*************************************************************** Checkout APIs *************************************************************************/


Route::get('/country_list/','AngularControllers\CheckoutController@getCountryList');
Route::get('/payment_methods/','AngularControllers\CheckoutController@get_payment_methods');
Route::get('/updatePaymentMethods','AngularControllers\CheckoutController@updatePaymentMethods');
Route::get('/shipping_methods/','AngularControllers\CheckoutController@get_shipping_methods');
Route::get('/customer_shipping_methods/','AngularControllers\CheckoutController@customer_shipping_methods');
Route::get('/get_order_itemlists/','AngularControllers\CheckoutController@get_itemlists');
Route::get('/get_order_itemlists_payment/','AngularControllers\CheckoutController@getOrderItem_finalPayment');
Route::get('/get_customer_address/','AngularControllers\CheckoutController@get_customer_address');
Route::get('/getCustomerDataWithoutLogin/','AngularControllers\CheckoutController@get_CustomerDataWithoutLogin');
Route::get('/getCustomerAddr/','AngularControllers\CheckoutController@getcustomeraddr');
Route::get('/billingandshipping/','AngularControllers\CheckoutController@get_billing_shipping_addr');
Route::get('/updateshippingaddr/','AngularControllers\CheckoutController@updateshippingaddr');
Route::get('/updatebillingaddr/','AngularControllers\CheckoutController@updatebillingaddr');
Route::get('/updatesameasbilling','AngularControllers\CheckoutController@updatesameasbilling');
Route::get('/getOrderDetails','AngularControllers\CheckoutController@getOrderDetails');
Route::get('/getCustomerOrderNumber','AngularControllers\CheckoutController@getOrderId');
Route::get('/getSelectedPaymentMethodDetils','AngularControllers\CheckoutController@getSelectedPaymentMethodDetils');
Route::get('/initiatePayment','AngularControllers\CheckoutController@initiatePayment');
Route::get('/updateVat','AngularControllers\CheckoutController@updateVat');
Route::post('/storeTransactionNumber','AngularControllers\CheckoutController@storeTransactionNumberInOrdertbl');
Route::get('/getcustomerorderid/','AngularControllers\CheckoutController@getcustomerorderid');
Route::post('/dynamicjsontoken/','AngularControllers\CheckoutController@dynamicjsontoken');
Route::post('/updateBillingshipping', function (Request $request ) {
	$result = App::call('\App\Http\Controllers\AngularControllers\CheckoutController@updateBillingshipping');
	return $result;
});
Route::get('/checkUserEmail/', function (Request $request ) {
   return App::call('\App\Http\Controllers\AngularControllers\CheckoutController@check_user_email');
});
Route::get('/updateOrderNotes/', function (Request $request ) {
   return App::call('\App\Http\Controllers\AngularControllers\CheckoutController@update_order_notes');
});
Route::get('/checkOrderNotes/', function (Request $request ) {
   return App::call('\App\Http\Controllers\AngularControllers\CheckoutController@check_order_notes');
});
Route::get('/getConfirmOrderDetails','AngularControllers\CheckoutController@getConfirmOrderDetails');

//  DEKO Payment getway
Route::post('/dekopaymentdata/','AngularControllers\ResponseController@dekopaymentdata');
Route::get('/getdekopaymentresponse/','AngularControllers\ResponseController@dekopaymentresponse');

//  PAYPAL Payment getway
Route::get('/getpaypalpaymentresponse/','AngularControllers\ResponseController@get_paypal_payment_response');

//  Sagepay Payment getway
Route::get('/get_sagepay_token/','AngularControllers\ResponseController@get_sagepay_tokendata');
Route::get('/makesagepay_payments/','AngularControllers\ResponseController@makesagepay_payments');

/*   FOR COUPON CODE    */
Route::get('/applyCoupon','AngularControllers\CouponController@applyCoupon');
Route::get('/removeCoupon','AngularControllers\CouponController@removeCoupon');

/*************************************************************** END Checkout APIs *****************************************************************************/

//Abondoned cart
Route::get('/save_abandoned_cartdata','AngularControllers\CheckoutController@storeAbandonedCartdata');

//---------- OPERATE AS CUSTOMER ---------------//
Route::get('/operateCustomer','AngularControllers\OperateCustomer@operateAsThisUser');
Route::post('/operateCustomerQuit','AngularControllers\OperateCustomer@operateCustomerQuit');

//---------- For Nrews Subscriber ---------------//
Route::get('/newsletterSubscribe','AngularControllers\CheckoutController@newsLetterSubscribe');
Route::post('/newsletter',['uses' => 'AngularControllers\Newsletters@storeEmails' ]);

/*************************************************************** MY ACCOUNT PROFILE SECTION *****************************************************************************/

Route::get('/getUserDataForContactus','AngularControllers\ContactUsController@getUserInfo');
Route::get('/deleteUserProfile', 'AngularControllers\CustomerRegistrationController@deleteUserProfile');
Route::get('/removeOrderIdAfterConfirm','AngularControllers\CheckoutController@removeOrderIdAfterConfirm');
Route::post('/address/{id}','AngularControllers\AfterLoginController@postAddr');
Route::post('/updateProfile/','AngularControllers\AfterLoginController@updateProfile');

// Forgot Password 
Route::post('/changedPassword','AngularControllers\CustomerRegistrationController@changedPassword');
Route::get('/checkChangedPasswordToken','AngularControllers\CustomerRegistrationController@checkChangedPasswordToken');
Route::post('/recoverPassword','AngularControllers\ContactUsController@sendmailforresetPassword');

// SITEMAP
Route::get('/getsitemapdata', 'AngularControllers\CommonController@getSitemapData');
Route::get('/get_loginuserdetails', 'AngularControllers\AfterLoginController@checkUser_islogedin');

//WIshLIST
Route::get('/wishlistData',[ 'uses' => 'AngularControllers\WishlistController@index']);
Route::get('/removeFromFavList', 'AngularControllers\WishlistController@removefavouriteList');
Route::get('/mailwishList/', function (Request $request) {
     $result = App::call('\App\Http\Controllers\AngularControllers\WishlistController@mailWishListItems');   return $result;
});
Route::get('/productAvailableInWishlist/', function (Request $request) {
      $result = App::call('\App\Http\Controllers\AngularControllers\ProductController@checkProductAvaialbility');  return $result;
 });