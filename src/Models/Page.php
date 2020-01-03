<?php

namespace PixelAdmin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	
	public function pages()
    {
        return $this->hasMany('PixelAdmin\Admin\Models\Page', 'parent_id', 'id');
    }
	
	public function contents()
    {
        return $this->hasMany('PixelAdmin\Admin\Models\PageContent');
    }
	
    public function gallery()
    {
        return $this->hasMany('PixelAdmin\Admin\Models\PageGallery');
    }
	
	public function pageType()
    {
        return $this->hasOne('PixelAdmin\Admin\Models\PageType', 'id', 'page_type_id');
    }
	
	public function parentPage()
	{
		return $this->belongsTo('PixelAdmin\Admin\Models\Page', 'parent_id');
	}
	
	public function getParentsNames() {
		if($this->parentPage) {
			
			return $this->parentPage->getParentsNames(). "||||" . $this->id;
		} else {
			return $this->id;
		}
	}
}
