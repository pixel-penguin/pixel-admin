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
	'namespace'		=> 	'PixelAdmin\Admin\Http\Controllers\Json',

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
	
});

//Admin
Route::group([
	'middleware'	=>	['web', 'checkifmainadmin'],
    'prefix'     	=> 	'admin/json',
	'namespace'		=> 	'PixelAdmin\Admin\Http\Controllers\Admin',

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


Route::get('cloudfiles/download/{id}',  'PixelAdmin\Admin\Http\Controllers\Json\WebsiteJsonController@downloadCloudFile');	
