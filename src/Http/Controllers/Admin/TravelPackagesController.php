<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\TravelPackage;
use PixelPenguin\Admin\Models\TravelPackageGallery;
use PixelPenguin\Admin\Models\TravelPackageTravelDate;

use Auth;

use Cloudder;

class TravelPackagesController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.travelpackages.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.travelpackages.edit', ['travelPackageId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.travelpackages.create');
	}
	
	public function destroy($id)
    {
        $page = TravelPackage::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		$redirect = false;
		
		//dd($input);
		
		if($id > 0){
			$travelPackage = TravelPackage::whereId($id)->first();
		}
		else{
			$travelPackage = new TravelPackage();
			$redirect = true;
		}
		
		$travelPackage->includes()->detach();
		$travelPackage->excludes()->detach();
		
		if(isset($input['data']['includes'])){
			
			foreach($input['data']['includes'] as $include){
				$travelPackage->includes()->attach($include['id']);
			}	
		}
		
		if(isset($input['data']['excludes'])){
			
			foreach($input['data']['excludes'] as $include){
				$travelPackage->excludes()->attach($include['id']);
			}	
		}
		
		
		$travelPackage->name = $input['data']['name'];
		$travelPackage->travel_package_type_id = $input['data']['travel_package_type_id'];
		$travelPackage->description = $input['data']['description'];
		$travelPackage->notes = $input['data']['notes'];
		$travelPackage->expire_date = $input['data']['expire_date'];
		$travelPackage->active = $input['data']['active'];
		$travelPackage->is_featured = $input['data']['is_featured'];
		
				
		$travelPackage->user_id = Auth::user()->id;
		
		
		$travelPackage->save();
		
		/*
		$travelPackage->categories()->detach();
		foreach($input['travel_package_category'] as $category){
			$travelPackage->categories()->attach($category['id']);
		}
		*/
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $travelPackage;
		$response['redirect'] = $redirect;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = TravelPackage::findOrFail($input['id']);
		
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

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_travel_package_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = TravelPackage::whereId($input['travel_package_id'])->first();
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

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_travelpackage_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new TravelPackageGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->travel_package_id = $input['travel_package_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$gallery = TravelPackageGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['travelPackageGallery'] as $gallery){
			$galleryEntry = travelPackageGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
	
	public function addTravelDate(Request $request){
		$input = $request->all();
		
		$id = $input['id'];
		$travelStartDate = $input['travelStartDate'];
		$travelEndDate = $input['travelEndDate'];
		
		$travelPackageTravelDate = new TravelPackageTravelDate();
				
		$travelPackageTravelDate->travel_package_id = $id;
		$travelPackageTravelDate->start_date = $travelStartDate;
		$travelPackageTravelDate->end_date = $travelEndDate;
		
		$travelPackageTravelDate->save();
		
		return [
			'success' => true
		];
	}
}
