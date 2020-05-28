<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\WebsiteDetail;

use Cloudder;

class WebsiteDetailsController extends Controller
{
    public function index()
    {
		
        return view('pixel-admin::admin.websitedetails.index');
    }
	
	public function update(Request $request){
		
		$input = $request->all();
		$input = $input['websiteDetail'];
		
		
		$websiteDetail = WebsiteDetail::first();
		
		
		$websiteDetail->name = $input['name'];
		$websiteDetail->slogan = $input['slogan'];
		$websiteDetail->logo_1_image_name = $input['logo_1_image_name'];
		$websiteDetail->logo_2_image_name = $input['logo_2_image_name'];
		$websiteDetail->color_1 = $input['color_1'];
		$websiteDetail->color_1_inverted = $input['color_1_inverted'];
		$websiteDetail->color_2 = $input['color_2'];
		$websiteDetail->color_2_inverted = $input['color_2_inverted'];
		$websiteDetail->color_3 = $input['color_3'];
		$websiteDetail->color_3_inverted = $input['color_3_inverted'];
		$websiteDetail->color_4 = $input['color_4'];
		$websiteDetail->color_4_inverted = $input['color_4_inverted'];
		$websiteDetail->physical_address = $input['physical_address'];
		$websiteDetail->email = $input['email'];
		$websiteDetail->contact_number = $input['contact_number'];
		$websiteDetail->working_hours = $input['working_hours'];
		$websiteDetail->facebook_url = $input['facebook_url'];
		$websiteDetail->instagram_url = $input['instagram_url'];
		$websiteDetail->linkedin_url = $input['linkedin_url'];
	
		$websiteDetail->save();
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
		
		$websiteDetail = WebsiteDetail::first();
		$websiteDetail->logo_1_image_name = $uploadedResult['public_id'];
		$websiteDetail->save();
		
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
		
		$websiteDetail = WebsiteDetail::first();
		$websiteDetail->logo_2_image_name = $uploadedResult['public_id'];
		$websiteDetail->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}

}
