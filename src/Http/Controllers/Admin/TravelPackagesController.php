<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\TravelPackage;
use PixelPenguin\Admin\Models\TravelPackageGallery;
use PixelPenguin\Admin\Models\TravelPackageTravelDate;
use PixelPenguin\Admin\Models\TravelPackageTravelDatePrice;
use PixelPenguin\Admin\Models\TravelPackageItinerary;
use PixelPenguin\Admin\Models\TravelPackageInclude;
use PixelPenguin\Admin\Models\TravelPackageExclude;

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
				
				if($include['id'] == 0){
					$includeCollection = new TravelPackageInclude();
					$includeCollection->user_id = Auth::user()->id;
					$includeCollection->name = $include['name'];
					$includeCollection->save();
					
					$travelPackage->includes()->attach($includeCollection->id);	
					
				}
				else{
					$travelPackage->includes()->attach($include['id']);	
				}
				
			}	
		}
		
		if(isset($input['data']['excludes'])){
			
			foreach($input['data']['excludes'] as $exclude){
				
				if($exclude['id'] == 0){
					$excludeCollection = new TravelPackageExclude();
					$excludeCollection->user_id = Auth::user()->id;
					$excludeCollection->name = $exclude['name'];
					$excludeCollection->save();
					
					$travelPackage->excludes()->attach($excludeCollection->id);	
					
				}
				else{
					$travelPackage->excludes()->attach($exclude['id']);
				}
				
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
	
	public function addTravelDatePrice(Request $request){
		$input = $request->all();
		
		//dd($input);
		
		$travelPackagePriceId = $input['travelPackagePriceId'];
		$travelPackagePriceName = $input['travelPackagePriceName'];
		$travelPackagePricePrice = $input['travelPackagePricePrice'];
		$travelPackagePriceType = $input['travelPackagePriceType'];
		
		$travelPackageTravelDatePrice = new TravelPackageTravelDatePrice();
				
		$travelPackageTravelDatePrice->travel_package_travel_date_id = $travelPackagePriceId;
		$travelPackageTravelDatePrice->name = $travelPackagePriceName;
		$travelPackageTravelDatePrice->price = $travelPackagePricePrice;
		$travelPackageTravelDatePrice->type = $travelPackagePriceType;
		
		$travelPackageTravelDatePrice->save();
		
		return [
			'success' => true
		];
	}
	
	public function addItinerary(Request $request){
		
		$input = $request->all();
		
		$itinerary = new TravelPackageItinerary();
		
		$itinerary->user_id = Auth::user()->id;
		$itinerary->travel_package_id = $input['travel_package_id'];
		
		$itinerary->save();
		
		return [
			'success' => true
		];
		
	}
	
	public function updateItinerary(Request $request){
		
		$input = $request->all();
		
		$itinerary = TravelPackageItinerary::whereId($input['id'])->first();;
		
		$itinerary->name =$input['name'];
		$itinerary->day =$input['day'];
		$itinerary->description =$input['description'];
		$itinerary->location =$input['location'];
		
		$itinerary->save();
		
		return [
			'success' => true
		];
		
	}
	
	public function orderItineraries(Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		$count = 0;
		foreach($input['content'] as $content){
			$pageContent = TravelPackageItinerary::whereId($content['id'])->first();
			$pageContent->column_order = $count;
			$pageContent->save();
			
			$count++;
		}
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		
	}
	
	public function deleteTravelDate($id){
		//dd($id);
		
		$gallery = TravelPackageTravelDate::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function deleteTravelDatePrice($id){
		//dd($id);
		
		$gallery = TravelPackageTravelDatePrice::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function deleteItinerary($id){
		//dd($id);
		
		$gallery = TravelPackageItinerary::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateImageName(Request $request){
		
		$input = $request->all();
		
		$travelPackageGallery = TravelPackageGallery::whereId($input['id'])->first();
		
		$travelPackageGallery->name = $input['name'];
		
		$travelPackageGallery->save();
		
		return [
			'success' => true
		];
		
		
	}
	
}
