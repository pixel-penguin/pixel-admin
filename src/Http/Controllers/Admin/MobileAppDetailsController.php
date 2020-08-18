<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\MobileAppDetail;

use Cloudder;

class MobileAppDetailsController extends Controller
{
    public function index()
    {
		
        return view('pixel-admin::admin.mobileappdetails.index');
    }
	
	public function update(Request $request){
		
		$input = $request->all();
		$input = $input['mobileAppDetail'];
		
		
		$mobileAppDetail = MobileAppDetail::first();
		
		
		$mobileAppDetail->name = $input['name'];
		$mobileAppDetail->slogan = $input['slogan'];
		$mobileAppDetail->logo_1_image_name = $input['logo_1_image_name'];
		$mobileAppDetail->logo_2_image_name = $input['logo_2_image_name'];
		$mobileAppDetail->color_1 = $input['color_1'];
		$mobileAppDetail->color_1_inverted = $input['color_1_inverted'];
		$mobileAppDetail->color_2 = $input['color_2'];
		$mobileAppDetail->color_2_inverted = $input['color_2_inverted'];
		$mobileAppDetail->color_3 = $input['color_3'];
		$mobileAppDetail->color_3_inverted = $input['color_3_inverted'];
		$mobileAppDetail->color_4 = $input['color_4'];
		$mobileAppDetail->color_4_inverted = $input['color_4_inverted'];
		$mobileAppDetail->physical_address = $input['physical_address'];
		$mobileAppDetail->email = $input['email'];
		$mobileAppDetail->contact_number = $input['contact_number'];
		$mobileAppDetail->working_hours = $input['working_hours'];
		$mobileAppDetail->facebook_url = $input['facebook_url'];
		$mobileAppDetail->instagram_url = $input['instagram_url'];
		$mobileAppDetail->linkedin_url = $input['linkedin_url'];
	
		$mobileAppDetail->save();
		return [
			'success' => true			
		];
		
	}
	
	public function updateImage1(Request $request){
		
		
		$input = $request->all();
		
		//dd($input);
		
		$this->validate($request,[
           'logo_1_image_name'=>'required|mimes:jpeg,bmp,jpg,png,svg|between:1, 50000',
       	]);

       	$logo_1_image_name = $request->file('logo_1_image_name');

       	$cloudder = Cloudder::upload($logo_1_image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'website_detail/'.str_slug($logo_1_image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$mobileAppDetail = MobileAppDetail::first();
		$mobileAppDetail->logo_1_image_name = $uploadedResult['public_id'];
		$mobileAppDetail->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateImage2(Request $request){
		
		
		$input = $request->all();
		
		//dd($input);
		
		$this->validate($request,[
           'logo_2_image_name'=>'required|mimes:jpeg,bmp,jpg,png,svg|between:1, 50000',
       	]);

       	$logo_2_image_name = $request->file('logo_2_image_name');

       	$cloudder = Cloudder::upload($logo_2_image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'website_detail/'.str_slug($logo_2_image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$mobileAppDetail = MobileAppDetail::first();
		$mobileAppDetail->logo_2_image_name = $uploadedResult['public_id'];
		$mobileAppDetail->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateImage3(Request $request){
		
		
		$input = $request->all();
		
		//dd($input);
		
		$this->validate($request,[
           'background_image'=>'required|mimes:jpeg,bmp,jpg,png,svg|between:1, 50000',
       	]);

       	$backgroundImage = $request->file('background_image');

       	$cloudder = Cloudder::upload($backgroundImage->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'mobile_app_detail/'.str_slug($backgroundImage->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$mobileAppDetail = MobileAppDetail::first();
		$mobileAppDetail->background_image = $uploadedResult['public_id'];
		$mobileAppDetail->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}

}
