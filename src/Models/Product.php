<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	public function prices()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProductPrice')->where('active', true)->orderBy('price');
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
        return $this->belongsTo('PixelPenguin\Admin\Models\ProductCategory', 'product_category_id', 'id');
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
