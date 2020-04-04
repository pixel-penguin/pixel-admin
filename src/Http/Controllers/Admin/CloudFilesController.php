<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\CloudFile;

use Auth;

use Cloudder;

class CloudFilesController extends Controller
{
    public function index(){
		
		
		return view('pixel-admin::admin.cloudfiles.index');
	}
	
	public function uploadFile(Request $request){
		
		$input = $request->all();
		
		$fileName = $request->file('file_name');

       	$cloudder = Cloudder::upload($fileName->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'cloud-files/'.str_slug($fileName->getClientOriginalName()).time(), ['resource_type' => 'raw'] );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$cloudFile =  new CloudFile();
		$cloudFile->name = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
		$cloudFile->file_name = $uploadedResult['public_id'];
		$cloudFile->file_extension = pathinfo($fileName->getClientOriginalName(), PATHINFO_EXTENSION);
		$cloudFile->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function destroy($id)
    {
        $cloudFile = CloudFile::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	
}
