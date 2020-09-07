<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\MobilePage;
use PixelPenguin\Admin\Models\MobilePageGallery;
use PixelPenguin\Admin\Models\MobilePageContent;
use PixelPenguin\Admin\Models\MobilePageContentType;
use PixelPenguin\Admin\Models\MobilePageContentGallery;

use Cloudder;

class MobilePagesController extends Controller
{
    private $orderNumber = 0;
   
    public function index()
    {
		
        return view('pixel-admin::admin.mobilepages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	
	
    public function orderPages(Request $request)
    {
		$input = $request->all();
		
		$pages = $input['pages'];





        foreach($pages as $page)
        {
            $this->updateFields($page);         
        }
		
		$response = array();
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateMobilePageImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('icon_image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_page_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = MobilePage::whereId($input['page_id'])->first();
		$page->icon_image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	private function updateFields($array, $id = 0)
    {
		
		
        $page = MobilePage::findOrFail($array['id']);
		
		$page->parent_id = $id;
		$page->column_order = $this->orderNumber;
		
		$page->save();
		
		$this->orderNumber++;
		
		if(isset($array['children']))
        {
            foreach($array['children'] as $child)
            {
                $this->updateFields($child, $array['id']);
            }
        }
		
		/*
        $aData = array(
        'page_id' => $id,
        'column_order' => $this->orderNumber
        );
		

        $this->orderNumber += 1;
        
        $oMainShared->tableUpdateOnID('u_page',$aData, $array['id']); 
		*/

        
    } 
	
    public function store(Request $request)
    {
        $input = $request->all();
		
		//dd($input);
		
		$page = new MobilePage();
		$page->user_id = Auth::user()->id;
		$page->name = $input['name'];
		
		$page->save();
		
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
		$page = MobilePage::whereId($id)->first();
		
        return view('pixel-admin::admin.mobilepages.edit',['page' => $page]);
    }
	
	public function pageBuilder($id)
    {
        
		$page = MobilePage::whereId($id)->first();
		
        return view('pixel-admin::admin.mobilepages.page_builder',['page' => $page]);
    }
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
		
		$page = MobilePage::whereId($id)->first();
		
		
		
		
		$page->name = $input['name'];
		//$page->mobile_page_type_id = $input['mobile_page_type_id'];
		//$page->website_link = $input['website_link'];
		//$page->link_name = $input['link_name'];
		$page->title = $input['title'];
		$page->mobile_page_type_id = $input['mobile_page_type_id'];
		//$page->meta_description = $input['meta_description'];
		//$page->description = $input['description'];
		
		
		//$page->no_link = $input['no_link'];
		$page->active = $input['active'];
		//$page->hidden = $input['hidden'];
		
		
		$page->save();		
		
		$response = array();
		$response['success'] = true;
		
		return $response;
		//$linkName = str_slug($page->name, '-');
		//$page->link_name = $linkName.'-'.$page->id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = MobilePage::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = MobilePage::findOrFail($input['id']);
		
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
	
	public function uploadGallery(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_page_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new MobilePageGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->page_id = $input['page_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function deleteGallery($id){
		//dd($id);
		
		$pageGallery = MobilePageGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function deleteBuilderGallery($id){
		//dd($id);
		
		$pageGallery = MobilePageContentGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateInfo(Request $request){
		$input = $request->all();
		$pageContentId = $input['page_content_id'];
		$description = $input['description'];
		$link = $input['link'];
		$extra = $input['extra'];
		
		$pageContent = MobilePageContent::whereId($pageContentId)->first();
		$pageContent->description = $description;
		$pageContent->link = $link;
		$pageContent->extra = $extra;
		
		$pageContent->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function addContentSection(Request $request){
		$input = $request->all();
		
		$pageId = $input['page_id'];
		$contentTypeId = $input['content_type_id'];
		
		$pageContent = new MobilePageContent();
		
		//dd(Auth::user());
		
		$pageContent->user_id = Auth::user()->id;
		$pageContent->page_content_type_id = $contentTypeId;
		$pageContent->page_id = $pageId;
		$pageContent->column_order = 900;
		$pageContent->description = 'Click to edit content';
		
		$pageContent->save();
		
		$response = array();
		
		$response['success'] = true;		
		$response['obj'] = $pageContent;
		
		return $response;		
		
		
	}
	
	public function removeContentSection($pageContentId){
		
		
		$pageContent = MobilePageContent::whereId($pageContentId)->first();
		
		$pageContent->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function uploadContentGallery(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	Cloudder::upload($image_name->getRealPath(),  env('CLOUDINARY_BASE_FOLDER_PATH').'app_page_images/'.str_slug($image_name->getClientOriginalName()).time());
		
		$result = Cloudder::getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$gallery = new MobilePageContentGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->mobile_page_content_id = $input['page_content_id'];
		$gallery->image_name = $result['public_id'];
		$gallery->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		//dd($input);
	}
	
	public function orderBuilderContent(Request $request){
		
		$input = $request->all();
		
		//dd($input);
		
		$count = 0;
		foreach($input['content'] as $content){
			$pageContent = MobilePageContent::whereId($content['id'])->first();
			$pageContent->column_order = $count;
			$pageContent->save();
			
			$count++;
		}
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
		
	}
	
	public function galleryOrder(Request $request, $pageId){
		$input = $request->all();
		
		//dd($input);
		$count = 0;
		
		foreach($input['pageGallery'] as $gallery){
			$galleryEntry = MobilePageGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
	
	public function updateContentBuilderImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png,svg|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'page_content_app/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($uploadedResult);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$pageContent = MobilePageContent::whereId($input['page_content_id'])->first();
		$pageContent->image_name = $uploadedResult['public_id'];
		$pageContent->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateGalleryName(Request $request){
		$input = $request->all();
		
		$page = MobilePageGallery::whereId($input['id'])->first();
		
		//dd($input);
		
		$page->name = $input['name'];
		$page->description = $input['description'];
		$page->button_text = $input['button_text'];
		$page->button_link = $input['button_link'];
		
		$page->save();
		
		
		
		return [
			'success' => true
		];
	}
	
	
	public function getPageContent($pageId)
	{
		//die('hi');
		$pageContents = MobilePageContent::where('page_id', $pageId)->with('gallery')->orderBy('column_order')->get();
		//dd($pageContents);
		foreach($pageContents as $content){
			$content->edit = false;
		}
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageContents;
		
        return $response;
	}
	
	public function getPageContentTypes()
	{
		$pageContentTypes = MobilePageContentType::where('active', true)->get();
			
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageContentTypes;
		
        return $response;
	}	
	
	
}
