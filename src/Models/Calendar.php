<?php

namespace PixelPenguin\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    public function categories()
    {
        return $this->belongsToMany('PixelPenguin\Admin\Models\CalendarCategory');
    }
}
