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
	'namespace'		=> 	'PixelAdmin\Admin\Http\Controllers\Admin',
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
});
