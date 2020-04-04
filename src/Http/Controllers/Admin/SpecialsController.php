<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\Special;

use Auth;

use Cloudder;

class SpecialsController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.specials.index', ['localAdmin' => true]);
	}
	
	public function edit($id){
		
		
		return view('pixel-admin::admin.specials.edit', ['specialId' => $id, 'localAdmin' => true]);
	}
	
	public function create(){
		
		
		return view('pixel-admin::admin.specials.create', ['localAdmin' => true]);
	}
	
	public function destroy($id)
    {
        $page = Special::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$special = Special::whereId($id)->first();
		}
		else{
			$special = new Special();
		}
		
		$special->name = $input['name'];
		$special->price = $input['price'];
		$special->time = $input['time'];
		$special->detail = $input['detail'];
		$special->active = $input['active'];
		$special->user_id = Auth::user()->id;
		
		$special->save();
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $special;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Special::findOrFail($input['id']);
		
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
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), 'app_special_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Special::whereId($input['special_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
}
