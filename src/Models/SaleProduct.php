<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class SaleProduct extends Model
{
    public function productPrice()
    {
        return $this->belongsTo('PixelPenguin\Admin\Models\ProductPrice');
    }
}
