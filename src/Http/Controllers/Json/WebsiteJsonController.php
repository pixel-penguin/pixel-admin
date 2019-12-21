<?php

namespace PixelAdmin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Page;
use App\PageGallery;
use App\Testimonial;
use App\Team;
use App\Blog;
use App\BlogGallery;
use App\BlogCategory;

use App\Calendar;
use App\CalendarGallery;
use App\CalendarCategory;

use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class WebsiteJsonController extends Controller
{
    public function submitContactForm(Request $request){
		
		$input = $request->all();
		
		$response = array();
		
		if($input['security1'] != $input['security2']){
			$response['success'] = false;
			$response['message'] = 'The security question you answered is wrong';
		}
		else{
			$message = $input['message']. '<br><br> My email is '.$input['email'].'<br><br> Regards, <br><br> '.$input['name'];
			$subject = $input['subject'];

			//Mail::to('seabreeze@seabreeze.com.na')->send(new Contact($message, $subject));
			Mail::to('gerrit@website-genius.com')->send(new Contact($message, $subject));
			//Mail::to('maja@swakopmund.life')->send(new Contact($message, $subject));
			//Mail::to('gerrit@website-genius.com')->send(new Contact($mail, $subject));



			$response['success'] = true;	
		}		
		
		return $response;
	}
	
    public function pages($showUnPublished = 'N')
	{
		if($showUnPublished == 'Y'){
			$pages = Page::orderBy('column_order', 'ASC')->get();	
		}
		else{
			$pages = Page::where('active', true)->orderBy('column_order', 'ASC')->get();	
		}
		
		
		foreach($pages as $page){
			if(strlen($page->website_link) > 0){
				$page->link_url = $page->website_link;				
			}
			else
			{
				$page->link_url = '/page/'.$page->link_name;
			}
		}
		
		//dd($pages);
		
		$netibleUsersMenu = $this->createArrayOfNestible($pages->toArray());
		
		foreach($netibleUsersMenu as $key => $menu)
		{
			//$netibleUsersMenu[$key]['has_children'] = false;
			
			if(isset($menu['children'][0]))
			{
				//$netibleUsersMenu[$key]['has_children'] = true;
			}
			
			
		}
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $netibleUsersMenu;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
    public function pageDetail($pageId)
	{
		$page = Page::whereId($pageId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $page;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
    public function pageGallery($pageId)
	{
		$pageGallery = PageGallery::where('page_id', $pageId)->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageGallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	private function createArrayOfNestible($tableArray)
	{
		$tree = array();
		$childs = array();    

		foreach($tableArray as &$item)
		{
			if(strlen($item['parent_id']) < 1)
			{
				$item['parent_id'] = 0;
			}
			$childs[$item['parent_id']][] = &$item;
			
			if(!isset($item['children'])){
				 $item['children'] = array();
			}
		}
		unset($item);

		
		//$item['children'] = array();
		
		foreach($tableArray as &$item) if (isset($childs[$item['id']]))
		{ 
			//dd($childs);
			$item['children'] = $childs[$item['id']];
			$item['has_children'] = true;
			unset($item);
		}	
		
		$tree = $childs[0];

		//echo '<pre>';
		//print_r($tree);
		//echo '</pre>';
		//die();

        return $tree;
	}
	
	public function testimonials($showUnPublished = false)
	{
		if($showUnPublished == true){
			$testimonials = Testimonial::All();	
		}
		else{
			$testimonials = Testimonial::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $testimonials;
		
		return $response;
		
	}
	
	public function getTestimonialDetail($testimonialId)
	{
		//die('hi');
		$testimonialDetail = Testimonial::whereId($testimonialId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $testimonialDetail;
		
        return $response;
	}
	
	public function blogCategories()
	{
		
		$blogCategories = BlogCategory::All();	
		
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $blogCategories;
		
		return $response;
		
	}
	
	public function blogs($showUnPublished = false)
	{
		if($showUnPublished == true){
			$blogs = Blog::All();	
		}
		else{
			$blogs = Blog::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $blogs;
		
		return $response;
		
	}
	
	public function getBlogDetail($id)
	{
		//die('hi');
		$blogDetail = Blog::whereId($id)->with('categories')->first();
				
			
		$blogDetail->simple_tags = explode(',', $blogDetail->tags);
		
		
		$complicatedTags = array();
		foreach($blogDetail->simple_tags as $key => $tag){
			$complicatedTags[$key]['key'] = $key;
			$complicatedTags[$key]['value'] = $tag;
		}
		
		$blogDetail->tags = $complicatedTags;
			
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $blogDetail;
		
        return $response;
	}
	
	
	
    public function blogGallery($id)
	{
		$gallery = BlogGallery::where('blog_id', $id)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $gallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	public function teams($showUnPublished = false)
	{
		if($showUnPublished == true){
			$teams = Team::All();	
		}
		else{
			$teams = Team::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $teams;
		
		return $response;
		
	}
	
	public function getTeamDetail($teamId)
	{
		//die('hi');
		$teamDetail = Team::whereId($teamId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $teamDetail;
		
        return $response;
	}
	
	
	/*+++++++++++++++++++++++++++++++++++++++
		
		Calendars
		
	++++++++++++++++++++++++++++++++++++++++*/
	
	
	public function calendarCategories()
	{
		
		$calendarCategories = CalendarCategory::All();	
		
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $calendarCategories;
		
		return $response;
		
	}
	
	public function calendars($showUnPublished = false)
	{
		if($showUnPublished == true){
			$calendars = Calendar::All();	
		}
		else{
			$calendars = Calendar::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $calendars;
		
		return $response;
		
	}
	
	public function getCalendarDetail($id)
	{
		//die('hi');
		$calendarDetail = Calendar::whereId($id)->with('categories')->first();
				
			
		$calendarDetail->simple_tags = explode(',', $calendarDetail->tags);
		
		
		$complicatedTags = array();
		foreach($calendarDetail->simple_tags as $key => $tag){
			$complicatedTags[$key]['key'] = $key;
			$complicatedTags[$key]['value'] = $tag;
		}
		
		$calendarDetail->tags = $complicatedTags;
			
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $calendarDetail;
		
        return $response;
	}
	
	
	
    public function calendarGallery($id)
	{
		$gallery = CalendarGallery::where('calendar_id', $id)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $gallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	
	
	
}
