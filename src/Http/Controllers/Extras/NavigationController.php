<?php

namespace PixelPenguin\Admin\Http\Controllers\Extras;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use PixelPenguin\Admin\Models\Page;
use PixelPenguin\Admin\Models\ProductCategory;

use Cloudder;

class NavigationController extends Controller
{
	/*
		In order to call this funciton, you need to call something similar to this inside your template:<br>
		
		{!! PixelPenguin\Admin\Http\Controllers\Extras\NavigationController::initiate('website.mobile') !!} // website.mobile being the template. It is automatically inserted inside your navigation folder inside your views folder.

	*/
	public static function initiate ($template = 'navigation.website.layout1')
	{
		
		$websiteJsonController = new \PixelPenguin\Admin\Http\Controllers\Json\WebsiteJsonController();
		
		$pages = Page::where('active', true)->where('parent_id', 0)->with('activeChildren')->orderBy('column_order', 'ASC')->get();
		
		//dd($pages->toArray());
		
		$htmlLayout = self::buildMenu($pages, false, $template);
		

		return $htmlLayout;
		
		//dd($htmlLayout);
		//die();
		
	}
	
	public static function initiateProductsMenu($template = 'navigation.website.layout1')
	{
		
		$websiteJsonController = new \PixelPenguin\Admin\Http\Controllers\Json\WebsiteJsonController();
		
		$pages = ProductCategory::where('parent_id', 0)->with('children')->orderBy('column_order', 'ASC')->get();
		
		//dd($pages->toArray());
		
		$htmlLayout = self::buildMenu($pages, false, $template);
		

		return $htmlLayout;
		
		//dd($htmlLayout);
		//die();
		
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
				
				if($collectionEntry){
					if($collectionEntry->website_link != null && strlen($collectionEntry->website_link) > 0){
						$link = $collectionEntry->website_link;
					}
					else{
						$link = '/page/'.$collectionEntry->link_name;
					}
				}
				
				$isMainParent = false;
				$hasChildren = false;
				
				if($collectionEntry->parent_id == 0){
					$isMainParent = true;	
				}
				
				if($sub != null){
					$hasChildren = true;
				}
				
				$currentPage = false;
								
				$menu .= view($template, ['navigation' => $collectionEntry, 'currentPage' => $currentPage, 'hasChildren' => $hasChildren, 'isMainParent' => $isMainParent, 'id' => $collectionEntry->id, 'name' => $collectionEntry->name, 'link' => $link, 'sub' => $sub]);
				
			}             

			return $menu;   

			//unset($url, $display, $sub);
		}
	}
}
