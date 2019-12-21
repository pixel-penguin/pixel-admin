<?php

namespace PixelAdmin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelAdmin\Admin\Models\Testimonial;

use Auth;

use Cloudder;

class TestimonialsController extends Controller
{
    public function index(){
		
		
		return view('admin.testimonials.index');
	}
	
	public function edit($id){
		
		
		return view('admin.testimonials.edit', ['testimonialId' => $id]);
	}
	
	public function create(){
		
		
		return view('admin.testimonials.create');
	}
	
	public function destroy($id)
    {
        $page = Testimonial::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$testimonial = Testimonial::whereId($id)->first();
		}
		else{
			$testimonial = new Testimonial();
		}
		
		$testimonial->name = $input['name'];
		$testimonial->position = $input['position'];
		$testimonial->detail = $input['detail'];
		$testimonial->active = $input['active'];
		$testimonial->user_id = Auth::user()->id;
		
		$testimonial->save();
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $testimonial;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Testimonial::findOrFail($input['id']);
		
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

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_testimonial_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Testimonial::whereId($input['testimonial_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
}
