<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class TravelPackageTravelDate extends Model
{
    public function prices()
    {
        return $this->hasMany('PixelPenguin\Admin\Models\TravelPackageTravelDatePrice');
    }
}
