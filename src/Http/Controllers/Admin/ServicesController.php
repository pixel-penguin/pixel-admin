<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Service;

use Auth;

use Cloudder;

class ServicesController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.services.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.services.edit', ['serviceId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.services.create');
	}
	
	public function destroy($id)
    {
        $page = Service::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$service = Service::whereId($id)->first();
		}
		else{
			$service = new Service();
		}
		
		$service->name = $input['name'];
		$service->detail = $input['detail'];
		$service->active = $input['active'];
		$service->user_id = Auth::user()->id;
		
		$service->save();
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $service;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Service::findOrFail($input['id']);
		
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
		
		$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_service_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Service::whereId($input['service_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
}
