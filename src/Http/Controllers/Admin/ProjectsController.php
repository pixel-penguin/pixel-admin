<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Project;
use PixelPenguin\Admin\Models\ProjectGallery;

use Auth;

use Cloudder;

class ProjectsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.projects.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.projects.edit', ['projectId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.projects.create');
	}
	
	public function destroy($id)
    {
        $page = Project::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		$redirect = false;
		
		//dd($input);
		
		if($id > 0){
			$project = Project::whereId($id)->first();
		}
		else{
			$project = new Project();
			$redirect = true;
		}
		
		$project->name = $input['name'];
		//$project->position = $input['position'];
		$project->detail = $input['detail'];
		$project->active = $input['active'];
		$project->date_created = $input['date_created'];
		$project->url = $input['url'];
		
		if(isset($input['detail_summary'])){
			$project->detail_summary = $input['detail_summary'];	
		}
		
		$project->user_id = Auth::user()->id;
		
		$cleanTags = array();
		
		foreach($input['tags'] as $tag){
			$cleanTags[] = $tag['value'];
		}
		
		$project->tags = implode(',', $cleanTags);
		
		
		$project->save();
		
		$project->categories()->detach();
		foreach($input['project_category'] as $category){
			$project->categories()->attach($category['id']);
		}
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $project;
		$response['redirect'] = $redirect;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Project::findOrFail($input['id']);
		
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

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_project_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Project::whereId($input['project_id'])->first();
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

       	$image_name = $request->file('image_name');

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_project_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new ProjectGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->project_id = $input['project_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$gallery = ProjectGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['projectGallery'] as $gallery){
			$galleryEntry = projectGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
}
