<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public function children()
	{
		return $this->hasMany('PixelPenguin\Admin\Models\ProductCategory', 'parent_id', 'id')->with('children');
	}
	
	public function parentCategory()
	{
		return $this->belongsTo('PixelPenguin\Admin\Models\ProductCategory', 'parent_id');
	}
	
	public function getParentsNames() {
		
		//echo '1-';
		if($this->parentCategory) {			
			return $this->parentCategory->getParentsNames(). "||||" . $this->id;
		} else {
			return $this->id;
		}
	}
}
