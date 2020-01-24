<?php

namespace PixelAdmin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelAdmin\Admin\Models\BlogCategory');
    }
	
	public function galleries()
    {
        return $this->belongsToMany('PixelAdmin\Admin\Models\BlogGallery');
    }
}
