<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    public function product()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\Product');
    }
	
	public function keywords()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProductPriceKeyword');
    }
	
	public function keywordsArray()
    {
        return $this->keywords()->pluck('name');
    }
}
