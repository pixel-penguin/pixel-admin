<?php

namespace PixelPenguin\Admin\Http\Controllers\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PixelPenguin\Admin\Models\MobilePage;
use PixelPenguin\Admin\Models\MobilePageGallery;
use PixelPenguin\Admin\Models\MobilePageContent;
use PixelPenguin\Admin\Models\MobilePageContentGallery;

class MobilePagesJsonController extends Controller
{
    
    public function pages($showUnPublished = 'N')
	{
		if($showUnPublished == 'Y'){
			$pages = MobilePage::orderBy('column_order', 'ASC')->get();	
		}
		else{
			$pages = MobilePage::where('active', true)->orderBy('column_order', 'ASC')->get();	
		}
		
		
		foreach($pages as $page){
			if(strlen($page->website_link) > 0){
				$page->link_url = $page->website_link;				
			}
			else
			{
				$page->link_url = '/page/'.$page->link_name;
			}
		}
		
		//dd($pages);
		
		$netibleUsersMenu = $this->createArrayOfNestible($pages->toArray(), true);
		
		foreach($netibleUsersMenu as $key => $menu)
		{
			//$netibleUsersMenu[$key]['has_children'] = false;
			
			if(isset($menu['children'][0]))
			{
				//$netibleUsersMenu[$key]['has_children'] = true;
			}
			
			
		}
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $netibleUsersMenu;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
    public function pageDetail($pageId)
	{
		$page = MobilePage::whereId($pageId)->first();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $page;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
    public function pageGallery($pageId)
	{
		$pageGallery = MobilePageGallery::where('mobile_page_id', $pageId)->orderBy('column_order')->get();
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageGallery;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	private function createArrayOfNestible($tableArray, $showChildren = false)
	{
		$tree = array();
		$childs = array();    

		foreach($tableArray as &$item)
		{
			if(strlen($item['parent_id']) < 1)
			{
				$item['parent_id'] = 0;
			}
			$childs[$item['parent_id']][] = &$item;
			
			
			if($showChildren){
				if(!isset($item['children'])){
					 $item['children'] = array();
				}	
			}
			
			
			
			$item['label'] = $item['name'];
		}
		unset($item);

		
		//$item['children'] = array();
		
		foreach($tableArray as &$item) if (isset($childs[$item['id']]))
		{ 
			//dd($childs);
			
			$item['children'] = $childs[$item['id']];
			$item['has_children'] = true;
			unset($item);
		}	
		
		$tree = $childs[0];

		//echo '<pre>';
		//print_r($tree);
		//echo '</pre>';
		//die();

        return $tree;
	}
}
