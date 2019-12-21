<?php

namespace PixelAdmin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelAdmin\Admin\Models\Team;

use Auth;

use Cloudder;

class TeamsController extends Controller
{
    public function index(){
		
		
		return view('admin.teams.index');
	}
	
	public function edit($id){
		
		
		return view('admin.teams.edit', ['teamId' => $id]);
	}
	
	public function create(){
		
		
		return view('admin.teams.create');
	}
	
	public function destroy($id)
    {
        $page = Team::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function update($id, Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		if($id > 0){
			$team = Team::whereId($id)->first();
		}
		else{
			$team = new Team();
		}
		
		$team->name = $input['name'];
		$team->position = $input['position'];
		$team->detail = $input['detail'];
		$team->active = $input['active'];
		$team->user_id = Auth::user()->id;
		
		$team->save();
		
		$response = array();
		
		$response['success'] =  true;
		$response['obj'] = $team;
		
		return $response;
	}
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Team::findOrFail($input['id']);
		
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
		
		$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_team_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Team::whereId($input['team_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
}
