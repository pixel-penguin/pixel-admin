<?php

namespace PixelPenguin\Admin;


use PixelPenguin\Admin\Http\Controllers\Json\WebsiteJsonController;

class Facade
{
    
    public static function getAllPages($showUnPublished = 'N')
    {
		$website = new WebsiteJsonController();
		
		$pages = $website->pages($showUnPublished);
		
		//dd($pages);
		
		return $pages['obj'];
    }
	
	
	 public static function pageDetail($pageId){
		$website = new WebsiteJsonController();
		
		$page = $website->pageDetail($pageId);
		
		//dd($pages);
		
		return $page['obj'];
    }
	
	 public static function pageGallery($pageId){
		$website = new WebsiteJsonController();
		
		$gallery = $website->pageGallery($pageId);
		
		//dd($pages);
		
		return $gallery['obj'];
    }
}