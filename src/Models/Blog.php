<?php

namespace PixelAdmin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelAdmin\Admin\Models\BlogCategory');
    }
	
	public function gallery()
    {
        return $this->hasMany('PixelAdmin\Admin\Models\BlogGallery');
    }
	
	public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
