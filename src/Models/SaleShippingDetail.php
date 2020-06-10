<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class SaleShippingDetail extends Model
{
    public function country()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\Country');
    }
	
	public function city()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\City');
    }
}
