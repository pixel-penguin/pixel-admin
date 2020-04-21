<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Brand;

use Auth;

use Cloudder;

class BrandsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.brands.index');
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.brands.edit', ['brandId' => $id]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.brands.create');
	}
	
	public function destroy($id)
    {
        $page = Brand::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$brand = Brand::whereId($id)->first();
		}
		else{
			$brand = new Brand();
		}
		
		$brand->name = $input['name'];
		$brand->detail = $input['detail'];
		$brand->active = $input['active'];
		$brand->user_id = Auth::user()->id;
		
		$brand->save();
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $brand;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Brand::findOrFail($input['id']);
		
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
		
		$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_brand_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Brand::whereId($input['brand_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
}
