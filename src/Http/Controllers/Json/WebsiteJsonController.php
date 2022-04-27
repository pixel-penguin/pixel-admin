<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Page;
use PixelPenguin\Admin\Models\PageGallery;
use PixelPenguin\Admin\Models\Testimonial;
use PixelPenguin\Admin\Models\Team;
use PixelPenguin\Admin\Models\Service;
use PixelPenguin\Admin\Models\Brand;
use PixelPenguin\Admin\Models\Blog;
use PixelPenguin\Admin\Models\BlogGallery;
use PixelPenguin\Admin\Models\BlogCategory;


use PixelPenguin\Admin\Models\Project;
use PixelPenguin\Admin\Models\ProjectGallery;
use PixelPenguin\Admin\Models\ProjectCategory;

use PixelPenguin\Admin\Models\Calendar;
use PixelPenguin\Admin\Models\CalendarGallery;
use PixelPenguin\Admin\Models\CalendarCategory;
use PixelPenguin\Admin\Models\CloudFile;

use PixelPenguin\Admin\Models\Special;

use PixelPenguin\Admin\Models\Product;
use PixelPenguin\Admin\Models\ProducttGallery;
use PixelPenguin\Admin\Models\ProductCategory;

use PixelPenguin\Admin\Models\ProductColor;
use PixelPenguin\Admin\Models\ProductAttribute;
use PixelPenguin\Admin\Models\ProductGallery;

use PixelPenguin\Admin\Models\WebsiteDetail;

use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class WebsiteJsonController extends Controller
{
	
	public function searchProduct(Request $request){
		
		$input = $request->all();
		
		$search = $input['search'];
				
		$products = Product::where('name', 'LIKE', "%$search%")->orWhere('tags', 'LIKE', "%$search%")->get();
		
		return [
			'success' => true,
			'data' => $products
		];
	}
	
    public function submitContactForm(Request $request){
		
		$input = $request->all();
		
		$response = array();
		
		if(isset($input['telephone'])){
			$telephoneMessage = 'My telephone number is '.$input['telephone'];
		}
		else{
			$telephoneMessage = '';
		}
		
		if($input['security1'] != $input['security2']){
			$response['success'] = false;
			$response['message'] = 'The security question you answered is wrong';
		}
		else{
			$message = $input['message']. '<br><br>
			My email is '.$input['email'].'<br><br>
			'.$telephoneMessage.'<br><br>
			Regards, <br><br> '.$input['name'];
			$subject = $input['subject'];

			Mail::to('gerrit@website-genius.com')->send(new Contact($message, $subject));
			
			$response['success'] = true;	
		}		
		
		return $response;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| Page
	|--------------------------------------------------------------------------
	|
	| Pages JSON
	|
	|--------------------------------------------------------------------------
	*/
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
		
		$netibleUsersMenu = $this->createArrayOfNestible($pages->toArray(), true);
		
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
		$pageGallery = PageGallery::where('page_id', $pageId)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageGallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	private function createArrayOfNestible($tableArray, $showChildren = false)
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
			
			
			if($showChildren){
				if(!isset($item['children'])){
					 $item['children'] = array();
				}	
			}
			
			
			
			$item['label'] = $item['name'];
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
	
	
	/*
	|--------------------------------------------------------------------------
	| Testimonials
	|--------------------------------------------------------------------------
	|
	| Testimonial JSON
	|
	|--------------------------------------------------------------------------
	*/
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
	
	/*
	|--------------------------------------------------------------------------
	| Blogs
	|--------------------------------------------------------------------------
	|
	| Blog JSON
	|
	|--------------------------------------------------------------------------
	*/
	
	public function blogCategories()
	{
		
		$blogCategories = BlogCategory::orderBy('created_at', "DESC")->get();	
		
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $blogCategories;
		
		return $response;
		
	}
	
	public function blogs($showUnPublished = false)
	{
		if($showUnPublished == true){
			$blogs = Blog::orderBy('created_at', 'DESC')->get();	
		}
		else{
			$blogs = Blog::orderBy('created_at', 'DESC')->where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $blogs;
		
		return $response;
		
	}
	
	public function blogsFilter(Request $request, $pageNumber, $resultsPerPage, $categoryIds = 'all'){
		
		$input = $request->all();
		
		
		$skip = ($pageNumber - 1) * $resultsPerPage;
		
		
		$search = false;
		if(isset($input['search'])){
			$search = $input['search'];
		}
		
		$blogs = Blog::select('blogs.*')
		->leftJoin('blog_blog_category', 'blog_blog_category.blog_id', 'blogs.id')
		
		->where('blogs.active', true);
		
		if($categoryIds != 'all'){
			$categoryIds = explode(",", $categoryIds);
			$blogs->whereIn('blog_blog_category.blog_category_id', $categoryIds);	
		}
		
		if($search != false){
			$blogs->where(function ($q) use ($search){
				$q->where('name', 'LIKE', "%$search%")
				->orWhere('detail', 'LIKE', "%$search%");
			});
		}
		
		$blogs->orderBy('created_at', 'DESC')
		->skip($skip)
		->limit($resultsPerPage)
		->groupBy('id');
		
		
		$filteredBlogs = $blogs->get();
		
		
		foreach($filteredBlogs as $blog){
			if($blog->author == null || strlen($blog->author) < 1){
				$blog->author = $blog->user->name;
			}
			
			if(count($blog->gallery) > 0){
				$blog->image_name = $blog->gallery()->orderBy('column_order')->first()->image_name;	
			}
			else{
				$blog->image_name = 'placeholders/placeholder-image';
			}
			
			$blog->detail_summary = substr(strip_tags($blog->detail), 0, 200);
			
			$blog->blog_date = $blog->created_at->format('j F Y');
		}
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $filteredBlogs;
		
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
	
	
	/*
	|--------------------------------------------------------------------------
	| Teams
	|--------------------------------------------------------------------------
	|
	| Team JSON
	|
	|--------------------------------------------------------------------------
	*/
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
	
	/*
	|--------------------------------------------------------------------------
	| Services
	|--------------------------------------------------------------------------
	|
	| Service JSON
	|
	|--------------------------------------------------------------------------
	*/
	public function services($showUnPublished = false)
	{
		if($showUnPublished == true){
			$services = Service::All();	
		}
		else{
			$services = Service::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $services;
		
		return $response;
		
	}
	
	public function getServiceDetail($serviceId)
	{
		//die('hi');
		$serviceDetail = Service::whereId($serviceId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $serviceDetail;
		
        return $response;
	}
	
	/*
	|--------------------------------------------------------------------------
	| Brands
	|--------------------------------------------------------------------------
	|
	| Brand JSON
	|
	|--------------------------------------------------------------------------
	*/
	public function brands($showUnPublished = false)
	{
		if($showUnPublished == true){
			$brands = Brand::All();	
		}
		else{
			$brands = Brand::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $brands;
		
		return $response;
		
	}
	
	public function getBrandDetail($brandId)
	{
		//die('hi');
		$brandDetail = Brand::whereId($brandId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $brandDetail;
		
        return $response;
	}
	/*
	|--------------------------------------------------------------------------
	| Calendars
	|--------------------------------------------------------------------------
	|
	| Calendar JSON
	|
	|--------------------------------------------------------------------------
	*/
	
	
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
	
	
	/*
	|--------------------------------------------------------------------------
	| Cloud Files
	|--------------------------------------------------------------------------
	|
	| Cloud File JSON
	|
	|--------------------------------------------------------------------------
	*/
	
	public function cloudFiles($showUnPublished = false)
	{
		$cloudFiles = CloudFile::All();	
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $cloudFiles;
		
		return $response;
		
	}
	
	
	
	public function downloadCloudFile($id){
		
		$cloudFile = CloudFile::whereId($id)->first();
		
		$urlToFile = 'https://res.cloudinary.com/'.env('CLOUDINARY_CLOUD_NAME').'/raw/upload/'.$cloudFile->file_name;
		//echo $cloudFile->name.'.'.$cloudFile->file_extension;
				
		if($cloudFile->file_extension == 'pdf'){
			return response()->withHeaders([
                'Content-Type' => 'application/pdf'
            ])->file($urlToFile);
		}
		else{
			return response()->streamDownload(function() use ($urlToFile){
				readfile($urlToFile);
			}, $cloudFile->name.'.'.$cloudFile->file_extension);	
		}
		
		
		
	}
	
	/*
	|--------------------------------------------------------------------------
	| Projects
	|--------------------------------------------------------------------------
	|
	| Project JSON
	|
	|--------------------------------------------------------------------------
	*/
	
	public function projectCategories()
	{
		
		$projectCategories = ProjectCategory::All();	
		
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $projectCategories;
		
		return $response;
		
	}
	
	public function projects($showUnPublished = false)
	{
		if($showUnPublished == true){
			$projects = Project::All();	
		}
		else{
			$projects = Project::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $projects;
		
		return $response;
		
	}
	
	
	public function getProjectDetail($id)
	{
		//die('hi');
		$projectDetail = Project::whereId($id)->with('categories')->first();
				
			
		$projectDetail->simple_tags = explode(',', $projectDetail->tags);
		
		
		$complicatedTags = array();
		foreach($projectDetail->simple_tags as $key => $tag){
			$complicatedTags[$key]['key'] = $key;
			$complicatedTags[$key]['value'] = $tag;
		}
		
		$projectDetail->tags = $complicatedTags;
			
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $projectDetail;
		
        return $response;
	}
	
	
	
    public function projectGallery($id)
	{
		$gallery = ProjectGallery::where('project_id', $id)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $gallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	public function specials($showUnPublished = false)
	{
		if($showUnPublished == true){
			$specials = Special::All();	
		}
		else{
			$specials = Special::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $specials;
		
		return $response;
		
	}
	
	public function getSpecialDetail($specialId)
	{
		//die('hi');
		$specialDetail = Special::whereId($specialId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $specialDetail;
		
        return $response;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| Product Categories
	|--------------------------------------------------------------------------
	|
	| Product Categories JSON
	|
	|--------------------------------------------------------------------------
	*/
    public function productCategories($showUnPublished = 'N', $productChildrenShow = false)
	{
		if($showUnPublished == 'Y'){
			$productCategories = ProductCategory::orderBy('column_order')->get();	
		}
		else{
			$productCategories = ProductCategory::orderBy('column_order')->get();	
		}
		
		$netibleUsersMenu = $this->createArrayOfNestible($productCategories->toArray(), $productChildrenShow);
		
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
    public function productCategoryDetail($productCategoryId)
	{
		$productCategory = ProductCategory::whereId($productCategoryId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $productCategory;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	/*
	|--------------------------------------------------------------------------
	| Products
	|--------------------------------------------------------------------------
	|
	| Product JSON
	|
	|--------------------------------------------------------------------------
	*/
	public function getProductDetail($id)
	{
		//die('hi');
		$productDetail = Product::whereId($id)->with('category')->with('colors')->with('attributes')->with('gallery')->with('prices.keywords')->first();
			
		foreach($productDetail->prices as $price){
			$price->keywordsArray = $price->keywordsArray();
		}
		
		foreach($productDetail->attributes()->withPivot('value')->get() as $keyOne => $attribute ){
			
			
			$attribute->attribute_expanded = explode(',', $attribute->pivot->value);
			//dd($attribute->toArray());
			$complicatedValue = array();
			foreach($attribute->attribute_expanded as $key => $attributeExpandedEntries){
				
				$complicatedValue[$key]['key'] = $key;
				$complicatedValue[$key]['value'] = $attributeExpandedEntries;
			}	
			
			$attribute->value = $complicatedValue;
			
			$productDetail->attributes[$keyOne]->value = $complicatedValue;
		}
		
		
		//dd($productDetail->toArray());
		
			
		$productDetail->simple_tags = explode(',', $productDetail->tags);
		
		
		$complicatedTags = array();
		foreach($productDetail->simple_tags as $key => $tag){
			$complicatedTags[$key]['key'] = $key;
			$complicatedTags[$key]['value'] = $tag;
		}
		
		$productDetail->tags = $complicatedTags;
			
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $productDetail;
		
        return $response;
	}
	
	public function ProductColors()
	{
		
		$productColors = ProductColor::All();	
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $productColors;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	public function ProductAttributes()
	{
		
		$productAttributes = ProductAttribute::All();	
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $productAttributes;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	public function productGallery($id)
	{
		$gallery = ProductGallery::where('product_id', $id)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $gallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	public function products($showUnPublished = false)
	{
		if($showUnPublished == true){
			$products = Product::All();	
		}
		else{
			$products = Product::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $products;
		
		return $response;
		
	}
	
	public function getWebsiteDetail(){
		
		$websiteDetail = WebsiteDetail::first();
		
		if($websiteDetail == null){
			$websiteDetail = new WebsiteDetail();
			$websiteDetail->save();
			
			$websiteDetail = WebsiteDetail::first();
		}
		
		return [
			'success' => true,
			'obj' => $websiteDetail
		];
	}
}
