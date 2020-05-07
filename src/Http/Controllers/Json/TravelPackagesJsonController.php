<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\TravelPackage;
use PixelPenguin\Admin\Models\TravelPackageGallery;
use PixelPenguin\Admin\Models\TravelPackageType;
use PixelPenguin\Admin\Models\TravelPackageInclude;
use PixelPenguin\Admin\Models\TravelPackageExclude;

class TravelPackagesJsonController extends Controller
{
    public function travelPackageCategories()
	{
		
		$travelPackageCategories = TravelPackageType::All();	
		
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $travelPackageCategories;
		
		return $response;
		
	}
	
	public function travelPackages($showUnPublished = false)
	{
		if($showUnPublished == true){
			$travelPackages = TravelPackage::All();	
		}
		else{
			$travelPackages = TravelPackage::where('active', true)->get();	
		}
		
		
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $travelPackages;
		
		return $response;
		
	}
	
	
	
	public function getTravelPackageDetail($id)
	{
		//die('hi');
		$travelPackageDetail = TravelPackage::whereId($id)->with('includes')->with('excludes')->with('gallery')->with('travelDates.prices')->first();
		
		$includes = TravelPackageInclude::All();
		$excludes = TravelPackageExclude::All();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $travelPackageDetail;
		$response['includes'] = $includes;
		$response['excludes'] = $excludes;
		
        return $response;
	}
	
	
	
    public function travelPackageGallery($id)
	{
		$gallery = TravelPackageGallery::where('travel_package_id', $id)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $gallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
}
