<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	
	public function pages()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\Page', 'parent_id', 'id');
    }
	
	public function contents()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\PageContent');
    }
	
    public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\PageGallery');
    }
	
	public function pageType()
    {
        return $this->hasOne('PixelPenguin\Admin\Models\PageType', 'id', 'page_type_id');
    }
	
	public function parentPage()
	{
		return $this->belongsTo('PixelPenguin\Admin\Models\Page', 'parent_id');
	}
	
	public function getParentsNames() {
		if($this->parentPage) {
			
			return $this->parentPage->getParentsNames(). "||||" . $this->id;
		} else {
			return $this->id;
		}
	}
	
	public function children()
	{
		return $this->hasMany('PixelPenguin\Admin\Models\Page', 'parent_id', 'id')->with('children');
	}
}
