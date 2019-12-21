<?php

namespace PixelAdmin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Cloudder;

class MceFileUploadController extends Controller
{
    public function index(Request $request){
		$input = $request->all();
		
		$this->validate($request,[
           'file'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
       	]);
			
       	$file = $request->file('file')->getRealPath();
		$name = $request->file('file');

       	Cloudder::upload($file,  env('CLOUDINARY_BASE_FOLDER_PATH').'app_mce_images/'.str_slug($name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		$response = array();
		
		$response['success'] = false;
		
		if(isset($result['url']))
		{
			$response['success'] = true;
			$response['location'] = ($result['url']);
		}
		
		return $response;		
		
	}
}
