<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Blog;
use PixelPenguin\Admin\Models\BlogGallery;

use Auth;

use Cloudder;

class BlogsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.blogs.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.blogs.edit', ['blogId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.blogs.create');
	}
	
	public function destroy($id)
    {
        $page = Blog::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$blog = Blog::whereId($id)->first();
		}
		else{
			$blog = new Blog();
		}
		
		$blog->name = $input['name'];
		//$blog->position = $input['position'];
		$blog->detail = $input['detail'];
		$blog->active = $input['active'];
		
		if(isset($input['author'])){
			$blog->author = $input['author'];	
		}
		
		$blog->user_id = Auth::user()->id;
		
		$cleanTags = array();
		
		foreach($input['tags'] as $tag){
			$cleanTags[] = $tag['value'];
		}
		
		$blog->tags = implode(',', $cleanTags);
		
		
		$blog->save();
		
		$blog->categories()->detach();
		foreach($input['blog_category'] as $category){
			$blog->categories()->attach($category['id']);
		}
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $blog;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Blog::findOrFail($input['id']);
		
		if($page['active'] == true)
		{
			$page->active = false;
		}
		else
		{
			$page->active = true;
		}
		
		$page->save();

		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_blog_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Blog::whereId($input['blog_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function uploadGallery(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name')->getRealPath();

       	Cloudder::upload($image_name,  env('CLOUDINARY_BASE_FOLDER_PATH').'app_blog_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new BlogGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->blog_id = $input['blog_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$gallery = BlogGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['blogGallery'] as $gallery){
			$galleryEntry = blogGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
}
