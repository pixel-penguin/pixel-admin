<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\BlogCategory');
    }
	
	public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\BlogGallery');
    }
	
	public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
