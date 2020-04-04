<?php

namespace PixelPenguin\Admin\Http\Controllers\Admin;

use Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use PixelPenguin\Admin\Models\AdminNavigation;

use PixelPenguin\Admin\Models\PageType;
use PixelPenguin\Admin\Models\PageContent;
use PixelPenguin\Admin\Models\PageContentType;

class AdminJsonController extends Controller
{
    private $orderNumber = 0;
	
	
	public function navigation()
	{
		$websiteNavigation = AdminNavigation::where('hidden', 'N')->orderBy('column_order', 'ASC')->get()->toArray();
		
		//dd($websiteNavigation);
		
		foreach($websiteNavigation as $key => $entry)
		{
			$arr = explode("/", $entry['url']);
			$first = $arr[0];
			
			if($first == 'clientadmin')
			{
				
				unset($arr[0]);
				$websiteNavigation[$key]['url'] =  implode('/',$arr);					
				
			}
			
		}
		
		
		$netibleUsersMenu = $this->createArrayOfNestibleAdmin($websiteNavigation);
		
		foreach($netibleUsersMenu as $key => $menu)
		{
			$netibleUsersMenu[$key]['has_children'] = false;
			
			if(isset($menu['children']))
			{
				$netibleUsersMenu[$key]['has_children'] = true;
			}
			
			
		}
		
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $netibleUsersMenu;
		
		return $response;
		
		//dd($netibleUsersMenu);
	}
	
	private function createArrayOfNestibleAdmin($tableArray)
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
		}
		unset($item);

		foreach($tableArray as &$item) if (isset($childs[$item['id']]))
		{ 
			//dd($childs);
			$item['children'] = $childs[$item['id']];
			unset($item);
		}	
		
		$tree = $childs[0];

		//echo '<pre>';
		//print_r($tree);
		//echo '</pre>';
		//die();

        return $tree;
	}
	
	public function getPageTypes()
	{
		$pageTypes = PageType::All();
			
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageTypes;
		
        return $response;
	}
	
	public function getPageContent($pageId)
	{
		//die('hi');
		$pageContents = PageContent::where('page_id', $pageId)->with('gallery')->orderBy('column_order')->get();
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
		$pageContentTypes = PageContentType::where('active', 'Y')->get();
			
		$response = array();
		
		$response['success'] = true;
		$response['obj'] = $pageContentTypes;
		
        return $response;
	}
	
	
	
}
