<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\Page;
use PixelPenguin\Admin\Models\PageGallery;
use PixelPenguin\Admin\Models\PageContent;
use PixelPenguin\Admin\Models\PageContentGallery;

use Cloudder;

class PagesController extends Controller
{
    private $orderNumber = 0;
   
    public function index()
    {
		
        return view('pixel-admin::admin.pages.index');
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
	
	public function updatePageImage(Request $request){
		
		$input = $request->all();
		
		$this->validate($request,[
           'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 50000',
       	]);

       	$image_name = $request->file('image_name');

       	$cloudder = Cloudder::upload($image_name->getRealPath(), env('CLOUDINARY_BASE_FOLDER_PATH').'app_page_images/'.str_slug($image_name->getClientOriginalName()).time() );
		
		$uploadedResult = $cloudder->getResult();
		
		//dd($result);
		
		//Cloudder::rename($result['public_id'], $toPublicId);
		
		$page = Page::whereId($input['page_id'])->first();
		$page->image_name = $uploadedResult['public_id'];
		$page->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	private function updateFields($array, $id = 0)
    {
		
		
        $page = Page::findOrFail($array['id']);
		
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
		
		$page = new Page();
		$page->user_id = Auth::user()->id;
		$page->name = $input['name'];
		
		$page->save();
		
		$linkName = str_slug($page->name, '-') ;
		
		$page->link_name = $linkName.'-'.$page->id;
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
        
		$page = Page::whereId($id)->first();
		
        return view('pixel-admin::admin.pages.edit',['page' => $page]);
    }
	
	public function pageBuilder($id)
    {
        
		$page = Page::whereId($id)->first();
		
        return view('pixel-admin::admin.pages.page_builder',['page' => $page]);
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
		
		$page = Page::whereId($id)->first();
		
		
		
		
		$page->name = $input['name'];
		$page->page_type_id = $input['page_type_id'];
		$page->website_link = $input['website_link'];
		$page->link_name = $input['link_name'];
		$page->title = $input['title'];
		$page->meta_description = $input['meta_description'];
		$page->detail = $input['detail'];
		
		if(isset($input['detail_summary'])){
			$page->detail_summary = $input['detail_summary'];	
		}
		if(isset($input['price'])){
			$page->price = $input['price'];
		}
		
		$page->no_link = $input['no_link'];
		$page->active = $input['active'];
		$page->hidden = $input['hidden'];
		
		if(strlen($input['link_name'] < 1)){
			
			$linkName = str_slug($page->name, '-');
			$page->link_name = $linkName.'-'.$page->id;
		}
		
		//d($input);
		
		if(strlen($input['title']) < 1){
			
			$page->title = $page->name;
		}
		
		
		if(strlen($input['meta_description']) < 1){
			
			$page->meta_description = substr(strip_tags($page->detail), 0, 255);
		}
		
		
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
        $page = Page::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
    }
	
	public function activate(Request $request)
	{
		$input = $request->all();
		
		$page = Page::findOrFail($input['id']);
		
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
		
		$gallery = new PageGallery();
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
		
		$pageGallery = PageGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function deleteBuilderGallery($id){
		//dd($id);
		
		$pageGallery = PageContentGallery::whereId($id)->delete();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function updateInfo(Request $request){
		$input = $request->all();
		$pageContentId = $input['page_content_id'];
		$detail = $input['detail'];
		
		$pageContent = PageContent::whereId($pageContentId)->first();
		$pageContent->detail = $detail;
		
		$pageContent->save();
		
		$response = array();
		
		$response['success'] = true;
		
		return $response;
	}
	
	public function addContentSection(Request $request){
		$input = $request->all();
		
		$pageId = $input['page_id'];
		$contentTypeId = $input['content_type_id'];
		
		$pageContent = new PageContent();
		
		//dd(Auth::user());
		
		$pageContent->user_id = Auth::user()->id;
		$pageContent->page_content_type_id = $contentTypeId;
		$pageContent->page_id = $pageId;
		$pageContent->column_order = 900;
		$pageContent->detail = 'Click to edit content';
		
		$pageContent->save();
		
		$response = array();
		
		$response['success'] = true;		
		$response['obj'] = $pageContent;
		
		return $response;		
		
		
	}
	
	public function removeContentSection($pageContentId){
		
		
		$pageContent = PageContent::whereId($pageContentId)->first();
		
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
		
		$gallery = new PageContentGallery();
		$gallery->user_id = Auth::user()->id;
		$gallery->page_content_id = $input['page_content_id'];
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
			$pageContent = PageContent::whereId($content['id'])->first();
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
			$galleryEntry = PageGallery::whereId($gallery['id'])->first();
			$galleryEntry->column_order = $count;
			$galleryEntry->save();
			
			$count++;
			
		}
		
		return [
			'success' => true
		];
	}
}
