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

Route::group([
	'middleware'	=>	'web',
    'prefix'     	=> 	'json',
	'namespace'		=> 	'PixelPenguin\Admin\Http\Controllers\Json',

	], function () {

	
	Route::get('pages', 'WebsiteJsonController@pages');							//Get list of pages
	Route::get('pages/{unpublished}', 'WebsiteJsonController@pages');			//Get list of pages published and unpublished ('Y' for parameter)
	Route::get('page/detail/{pageId}', 'WebsiteJsonController@pageDetail');		//Get Page Detail
	Route::get('page/gallery/{pageId}', 'WebsiteJsonController@pageGallery');	//Get Page Gallery

	Route::post('submitcontactform',  'WebsiteJsonController@submitContactForm');				//Submit Contact us Form
	
	Route::get('testimonials',  'WebsiteJsonController@testimonials');							//Get list of testimonials
	Route::get('testimonials/{unpublished}',  'WebsiteJsonController@testimonials');			//Get list of tesimonials published and unpublished ('Y' for parameter)
	Route::get('testimonial/detail/{pageId}', 'WebsiteJsonController@getTestimonialDetail');	//Get get detail of testimonial
	
	//Route::get('blogs',  'WebsiteJsonController@blogs');																//Get list of blogs
	Route::get('blogs/{unpublished?}',  'WebsiteJsonController@blogs');													//Get list of blogs published and unpublished (Y for parameter)
	Route::get('blog/detail/{pageId}', 'WebsiteJsonController@getBlogDetail');	 										//Get blog detail
	Route::get('blog/gallery/{id}',  'WebsiteJsonController@blogGallery');												//Get list of blog galleries	
	Route::get('blogcategories/get',  'WebsiteJsonController@blogCategories'); 											//Get list of blog categories	
	Route::post('blogs/filter/{pageNumber}/{resultsPerPage}/{categoryIds?}',  'WebsiteJsonController@blogsFilter'); 	//Get filtered list of blogs. Also use param "search" in order to filter it in  blogs (in name and detail)

	Route::get('teams',  'WebsiteJsonController@teams');							//Get list of teams
	Route::get('teams/{unpublished}',  'WebsiteJsonController@teams');			//Get list of tesimonials published and unpublished ('Y' for parameter)
	Route::get('team/detail/{pageId}', 'WebsiteJsonController@getTeamDetail');	//Get get detail of team
	
	Route::get('calendars',  'WebsiteJsonController@calendars');							//Get list of calendars
	Route::get('calendars/{unpublished}',  'WebsiteJsonController@calendars');				//Get list of calendars published and unpublished (Y for parameter)
	Route::get('calendar/detail/{pageId}', 'WebsiteJsonController@getCalendarDetail');	 	//Get calendar detail
	Route::get('calendar/gallery/{id}',  'WebsiteJsonController@calendarGallery');			//Get list of calendar galleries	
	Route::get('calendarcategories/get',  'WebsiteJsonController@calendarCategories'); 			//Get list of calendar categories
	
	Route::get('cloudfiles',  'WebsiteJsonController@cloudFiles'); 			//Get list of cloud files
	
	Route::get('projects/{unpublished?}',  'WebsiteJsonController@projects');													//Get list of projects published and unpublished (Y for parameter)
	Route::get('project/detail/{pageId}', 'WebsiteJsonController@getProjectDetail');	 										//Get project detail
	Route::get('project/gallery/{id}',  'WebsiteJsonController@projectGallery');												//Get list of project galleries	
	Route::get('projectcategories/get',  'WebsiteJsonController@projectCategories'); 											//Get list of project categories	
	
	Route::get('specials',  'WebsiteJsonController@specials');	
	Route::get('specials/{unpublished}',  'WebsiteJsonController@specials');	
	Route::get('special/detail/{pageId}', 'WebsiteJsonController@getSpecialDetail');	
	
	Route::get('productcategories/detail/{pageId}', 'WebsiteJsonController@productCategoryDetail');								//Get Page Detail	
	Route::get('productcategories/{unpublished?}/{createChildren?}', 'WebsiteJsonController@productCategories');				//Get list of pages published and unpublished ('Y' for parameter)
	

	Route::get('products/{unpublished?}',  'WebsiteJsonController@products');													//Get list of products published and unpublished (Y for parameter)
	Route::get('product/detail/{productId}', 'WebsiteJsonController@getProductDetail');	 										//Get product detail
	Route::get('product/gallery/{id}',  'WebsiteJsonController@productGallery');												//Get list of product galleries	
	Route::get('productcategories/get',  'WebsiteJsonController@productCategories'); 											//Get list of product categories	
	
	Route::get('productcolors/get',  'WebsiteJsonController@ProductColors'); 											//Get list of product categories	
	Route::get('productattributes/get',  'WebsiteJsonController@ProductAttributes'); 											//Get list of product categories	
	
	
	Route::get('services',  'WebsiteJsonController@services');							//Get list of services
	Route::get('services/{unpublished}',  'WebsiteJsonController@services');			//Get list of tesimonials published and unpublished ('Y' for parameter)
	Route::get('service/detail/{pageId}', 'WebsiteJsonController@getServiceDetail');	//Get get detail of service
	
	Route::get('brands',  'WebsiteJsonController@brands');							//Get list of brands
	Route::get('brands/{unpublished}',  'WebsiteJsonController@brands');			//Get list of tesimonials published and unpublished ('Y' for parameter)
	Route::get('brand/detail/{pageId}', 'WebsiteJsonController@getBrandDetail');	//Get get detail of brand
	
	Route::get('travelpackages/{unpublished?}',  'TravelPackagesJsonController@travelPackages');													//Get list of projects published and unpublished (Y for parameter)
	Route::get('travelpackage/detail/{pageId}', 'TravelPackagesJsonController@getTravelPackageDetail');	 										//Get project detail
	Route::get('travelpackage/gallery/{id}',  'TravelPackagesJsonController@travelPackageGallery');												//Get list of project galleries	
	Route::get('travelpackagecategories/get',  'TravelPackagesJsonController@travelPackageCategories'); 											//Get list of project categories	
	
	Route::get('websitedetail/get', 'WebsiteJsonController@getWebsiteDetail');
});

//Admin
Route::group([
	'middleware'	=>	['web', 'checkifmainadmin'],
    'prefix'     	=> 	'admin/json',
	'namespace'		=> 	'PixelPenguin\Admin\Http\Controllers\Admin',

	], function () {

	
	//Admin Json	
	Route::get('navigation',  'AdminJsonController@navigation');	
	Route::get('getpagetypes',  'AdminJsonController@getPageTypes');	
	Route::get('getpagecontents/{pageid}',  'AdminJsonController@getPageContent');	
	Route::post('addcontentsection',  'PagesController@addContentSection');	
	Route::delete('removecontentsection/{pagecontentid}',  'PagesController@removeContentSection');	
	
	Route::get('getpagecontenttypes',  'AdminJsonController@getPageContentTypes');	
	
	//Route::get('gettestimonialdetail',  'AdminJsonController@getTestimonialDetail');	
	
	
	
});


Route::get('cloudfiles/download/{id}',  'PixelPenguin\Admin\Http\Controllers\Json\WebsiteJsonController@downloadCloudFile');	
