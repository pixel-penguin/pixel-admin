<?php

namespace PixelPenguin\Admin\Http\Controllers\Extras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\AdminNavigation;

use Cloudder;

use URL;

class AdminNavigationController extends Controller
{
	/*
		In order to call this funciton, you need to call something similar to this inside your template:<br>
		
		{!! PixelPenguin\Admin\Http\Controllers\Extras\NavigationController::initiate('website.mobile') !!} // website.mobile being the template. It is automatically inserted inside your navigation folder inside your views folder.

	*/
	public static function initiate ($template = 'pixel-admin::navigation.admin')
	{
		
		$adminNavigation = AdminNavigation::where('active', true)->where('parent_id', 0)->with('children')->orderBy('column_order', 'ASC')->get();
		
		//dd($pages->toArray());
		
		$htmlLayout = self::buildMenu($adminNavigation, false, $template);
		
		//dd($htmlLayout);
		//die();

		return $htmlLayout;
		
		
		
	}
	
	
	private static function buildMenu($collection, $is_sub = false, $template)
	{
		$menu = '';                                                           

		/*
		* Loop through the array to extract element values
		*/

		if(isset($collection[0]))
		{
			foreach($collection as $key => $collectionEntry) {
				
				$subProducts = '';

				if(isset($collectionEntry->children[0]))
				{					
					//echo 'There is a page with a sub page';
					//die();
					$sub = self::buildMenu($collectionEntry->children, true, $template);
				}
				else
				{						
					$sub = NULL;
					//$$key = collectionEntry->id;
				}
				/*
				* If no array element had the key 'url', set the
				* $url variable equal to the containing element's ID
				*/
				$expand = '';
				$class = '';

				$encodedName = $collectionEntry['name'];
				$encodedName = str_replace("&", "and", $encodedName); 
				$encodedName = str_replace(" ", "-", $encodedName); 
				
				$link = $collectionEntry->url;
				
				$isMainParent = false;
				$hasChildren = false;
				
				if($collectionEntry->parent_id == 0){
					$isMainParent = true;	
				}
				
				if($sub != null){
					$hasChildren = true;
				}
				
				$link = parse_url(URL::current());
				//print_r($link);
				//die();
				
				$active = false;
				
				$currentLink = $link['path'];
				$currentLink = explode("/", $currentLink);
				$currentLink = '/'.$currentLink[1].'/'.$currentLink[2];
				
				//echo $currentLink;
				//die();
				
				
				
				
				if('/'.$collectionEntry->url == $currentLink){
					$active = true;
				}
				
				$currentPage = false;
				
				$menu .= view($template, ['navigation' => $collectionEntry, 'currentPage' => $currentPage, 'hasChildren' => $hasChildren, 'isMainParent' => $isMainParent, 'id' => $collectionEntry->id, 'name' => $collectionEntry->name, 'link' => $link, 'sub' => $sub, 'active' => $active]);
				
			}             

			return $menu;   

			//unset($url, $display, $sub);
		}
	}
}
