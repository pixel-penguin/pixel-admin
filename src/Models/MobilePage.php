<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class MobilePage extends Model
{
	
	public function pages()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\MobilePage', 'parent_id', 'id');
    }
	
	public function contents()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\MobilePageContent');
    }
	
    public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\MobilePageGallery');
    }
	
	public function pageType()
    {
        return $this->hasOne('PixelPenguin\Admin\Models\MobilePageType', 'id', 'page_type_id');
    }
	
	public function parentPage()
	{
		return $this->belongsTo('PixelPenguin\Admin\Models\MobilePage', 'parent_id');
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
		return $this->hasMany('PixelPenguin\Admin\Models\MobilePage', 'parent_id', 'id')->with('children');
	}
	
	public function activeChildren()
	{
		return $this->hasMany('PixelPenguin\Admin\Models\MobilePage', 'parent_id', 'id')->where('active', true)->with('children');
	}
}
