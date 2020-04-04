<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceKeyword extends Model
{
    public function keywords()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\ProductPriceKeyword');
    }
}
