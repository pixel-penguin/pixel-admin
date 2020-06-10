<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/


//Route::get('/home', 'HomeController@index');

/*
Route::get('/tester', function () {
	return 'hi there';
});
*/

Route::group([
	'namespace'		=> 	'PixelPenguin\Admin\Http\Controllers\Admin',
	//'middleware'	=>	'auth',
	'middleware'	=>	['web', 'checkifmainadmin'],
    'prefix'    	=> 	'admin',
    //'namespace'		=> 	'Admin'

	], function () {

	
	
	Route::get('/', function () {
		return redirect()->route('admin.dashboard.index');
	});
	
	Route::post('mcefileupload', 'MceFileUploadController@index');
	
	//User Dashboard
	Route::get('dashboard',  'DashboardController@index')->name('admin.dashboard.index');	
	Route::get('dashboard/json/get-analytics/{startDate}/{endDate}',  'DashboardController@analytic');	
	
	Route::post('pages/updatepageimage', 'PagesController@updatePageImage');
	
	Route::delete('pages/builder/gallery/delete/{galleryId}', 'PagesController@deleteBuilderGallery');
	Route::delete('pages/gallery/delete/{galleryId}', array('as' => 'admin.pages.gallery.delete', 'uses' => 'PagesController@deleteGallery'));
	Route::post('pages/uploadgallery', array('as' => 'admin.pages.uploadgallery', 'uses' => 'PagesController@uploadGallery'));
	Route::post('pages/activate', array('as' => 'admin.pages.activate', 'uses' => 'PagesController@activate'));
	Route::post('pages/order',  array('as' => 'admin.pages.order', 'uses' => 'PagesController@orderPages'));	
	Route::post('pages/builder/order', 'PagesController@orderBuilderContent');
	Route::post('pages/gallery/order/{pageId}', 'PagesController@galleryOrder'); 
	Route::post('pages/gallery/name/update', 'PagesController@updateGalleryName'); 
	Route::post('pages/builder/image/update', 'PagesController@updateContentBuilderImage'); 
	
	Route::post('pages/builder/updateinfo', 'PagesController@updateInfo');
	Route::post('pages/builder/uploadgallery', 'PagesController@uploadContentGallery');
	
	Route::get('pages/builder/{pageId}', 'PagesController@pageBuilder');	
	
	Route::resource('pages', 'PagesController');
	
	Route::resource('testimonials', 'TestimonialsController');
	Route::post('testimonials/activate',  'TestimonialsController@activate');	
	Route::post('testimonials/updateimage', 'TestimonialsController@updateImage');
	
	Route::resource('teams', 'TeamsController');
	Route::post('teams/activate',  'TeamsController@activate');	
	Route::post('teams/updateimage', 'TeamsController@updateImage');
	
	
	Route::resource('blogs', 'BlogsController');
	Route::post('blogs/activate',  'BlogsController@activate');	
	Route::delete('blogs/gallery/delete/{galleryId}','BlogsController@deleteGallery');
	Route::post('blogs/gallery/upload', 'BlogsController@uploadGallery');
	Route::post('blogs/gallery/order/{id}', 'BlogsController@galleryOrder'); 
	
	
	Route::resource('calendars', 'CalendarsController');
	Route::post('calendars/activate',  'CalendarsController@activate');	
	Route::delete('calendars/gallery/delete/{galleryId}','CalendarsController@deleteGallery');
	Route::post('calendars/gallery/upload', 'CalendarsController@uploadGallery');
	Route::post('calendars/gallery/order/{id}', 'CalendarsController@galleryOrder');
	
	
	Route::resource('projects', 'ProjectsController');
	Route::post('projects/activate',  'ProjectsController@activate');	
	Route::delete('projects/gallery/delete/{galleryId}','ProjectsController@deleteGallery');
	Route::post('projects/gallery/upload', 'ProjectsController@uploadGallery');
	Route::post('projects/gallery/order/{id}', 'ProjectsController@galleryOrder'); 
	
	
	Route::get('cloudfiles', 'CloudFilesController@index'); 
	Route::post('cloudfiles/file/upload', 'CloudFilesController@uploadFile');
	Route::delete('cloudfiles/{id}', 'CloudFilesController@destroy');
	
	
	Route::resource('specials', 'SpecialsController');
	Route::post('specials/activate',  'SpecialsController@activate');	
	Route::post('specials/updateimage', 'SpecialsController@updateImage');
	
	
	Route::resource('productcategories', 'ProductCategoriesController');
	Route::post('productcategories/activate',  'ProductCategoriesController@activate');	
	Route::post('productcategories/order', 'ProductCategoriesController@orderProductCategories');
	
	Route::resource('products', 'ProductsController');
	Route::post('products/activate',  'ProductsController@activate');	
	Route::delete('products/gallery/delete/{galleryId}','ProductsController@deleteGallery');
	Route::post('products/gallery/upload', 'ProductsController@uploadGallery');
	Route::post('products/gallery/order/{id}', 'ProductsController@galleryOrder'); 
	
	Route::post('products/price/update', 'ProductsController@updatePrices'); 
	
	Route::resource('services', 'ServicesController');
	Route::post('services/activate',  'ServicesController@activate');	
	Route::post('services/updateimage', 'ServicesController@updateImage');
	
	Route::resource('brands', 'BrandsController');
	Route::post('brands/activate',  'BrandsController@activate');	
	Route::post('brands/updateimage', 'BrandsController@updateImage');
	
	Route::resource('travelpackages', 'TravelPackagesController');
	Route::post('travelpackages/image/name/update',  'TravelPackagesController@updateImageName');	
	Route::post('travelpackages/addtraveldate',  'TravelPackagesController@addTravelDate');	
	Route::post('travelpackages/addtraveldateprice',  'TravelPackagesController@addTravelDatePrice');	
	Route::post('travelpackages/additinerary',  'TravelPackagesController@addItinerary');	
	Route::post('travelpackages/updateitinerary',  'TravelPackagesController@updateItinerary');	
	Route::post('travelpackages/itinerary/order', 'TravelPackagesController@orderItineraries');
	
	Route::post('travelpackages/activate',  'TravelPackagesController@activate');	
	Route::delete('travelpackages/gallery/delete/{galleryId}','TravelPackagesController@deleteGallery');
	Route::post('travelpackages/gallery/upload', 'TravelPackagesController@uploadGallery');
	Route::post('travelpackages/gallery/order/{id}', 'TravelPackagesController@galleryOrder');
	
	Route::delete('travelpackages/traveldates/delete/{id}', 'TravelPackagesController@deleteTravelDate');
	Route::delete('travelpackages/traveldates/price/delete/{id}', 'TravelPackagesController@deleteTravelDatePrice');
	Route::delete('travelpackages/itinerary/delete/{id}', 'TravelPackagesController@deleteItinerary');
	
	Route::get('websitedetail', 'WebsiteDetailsController@index');
	Route::post('websitedetail/update', 'WebsiteDetailsController@update');
	Route::post('websitedetail/updatepageimage1', 'WebsiteDetailsController@updateImage1');
	Route::post('websitedetail/updatepageimage2', 'WebsiteDetailsController@updateImage2');
	
	
	Route::get('orders', 'OrdersController@index');
	Route::post('orders/changestatus', 'OrdersController@changeOrderStatus');
	Route::get('orders/get/{type}', 'OrdersController@getOrders');
	Route::post('orders/updatedelivery', 'OrdersController@updateDelivery');
	Route::post('orders/updatepayment', 'OrdersController@updatePayments');
	
});
