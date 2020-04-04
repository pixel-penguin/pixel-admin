<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	public function prices()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProductPrice');
    }
	
	public function colors()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\ProductColor');
    }
	
	public function attributes()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\ProductAttribute');
    }
	
    public function category()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\ProductCategory');
    }
	
	public function gallery()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProductGallery');
    }
	
	public function user()
    {
        return $this->hasOne('App\User','id','user_id');
    }
}
