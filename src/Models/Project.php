<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\ProjectCategory');
    }
	
	public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProjectGallery');
    }
	
	public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
