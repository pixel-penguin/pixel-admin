<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    public function products()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\SaleProduct');
    }
	
	public function ShippingDetail()
    {
        return $this->hasOne('PixelPenguin\Admin\Models\SaleShippingDetail');
    }
	
	public function shippingMethod()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\SaleShippingMethod', 'sale_shipping_method_id', 'id');
    }
	
	public function user()
    {
        return $this->belongsTo('App\User');
    }
}
